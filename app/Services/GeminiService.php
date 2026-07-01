<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * GeminiService
 *
 * Mengirim foto makanan ke Gemini API (model gratis: gemini-1.5-flash / gemini-2.0-flash)
 * dan meminta estimasi nutrisi dalam format JSON terstruktur.
 *
 * Dapatkan API key gratis di https://aistudio.google.com/apikey
 * Simpan sebagai GEMINI_API_KEY di file .env
 */
class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        // gemini-2.0-flash: kuota gratis paling besar per Juli 2026, cocok untuk vision task ringan
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
    }

    /**
     * Analisis foto makanan dan kembalikan estimasi nutrisi.
     *
     * @param string $imagePath path file lokal (storage/app/...)
     * @return array{food_name:string, calories:int, protein_g:int, carbs_g:int, fat_g:int, fiber_g:int, sugar_g:int, sodium_mg:int, confidence:float}
     */
    public function analyzeFoodPhoto(string $imagePath): array
    {
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath) ?: 'image/jpeg';

        $prompt = <<<PROMPT
        Kamu adalah ahli gizi. Amati foto makanan ini dan berikan estimasi nutrisi TERBAIK
        yang bisa kamu perkirakan dari tampilan visual (porsi, jenis bahan, cara masak).

        Balas HANYA dengan JSON valid, tanpa teks tambahan, tanpa markdown, dengan struktur persis berikut:
        {
        "food_name": "nama makanan singkat dalam Bahasa Indonesia",
        "calories": <integer, kkal>,
        "protein_g": <integer, gram>,
        "carbs_g": <integer, gram>,
        "fat_g": <integer, gram>,
        "fiber_g": <integer, gram>,
        "sugar_g": <integer, gram>,
        "sodium_mg": <integer, miligram>,
        "confidence": <float 0-1, seberapa yakin kamu dengan estimasi ini>
        }

        Jika gambar bukan makanan/minuman, kembalikan semua nilai nutrisi 0 dan food_name "unknown".
        PROMPT;

        $response = Http::timeout(120)
            ->withoutVerifying()
            ->post(
            "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}",
            [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $imageData,
                            ]
                        ],
                    ],
                ]],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json',
                ],
            ]
        );

        if ($response->failed()) {
            Log::error('Gemini API error', ['body' => $response->body()]);
            throw new RuntimeException('Gagal menganalisis foto makanan: ' . $response->status());
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (! $text) {
            throw new RuntimeException('Respon Gemini tidak berisi data nutrisi.');
        }

        $data = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Gagal parsing JSON dari Gemini: ' . json_last_error_msg());
        }

        return [
            'food_name'  => $data['food_name'] ?? 'unknown',
            'calories'   => (int) ($data['calories'] ?? 0),
            'protein_g'  => (int) ($data['protein_g'] ?? 0),
            'carbs_g'    => (int) ($data['carbs_g'] ?? 0),
            'fat_g'      => (int) ($data['fat_g'] ?? 0),
            'fiber_g'    => (int) ($data['fiber_g'] ?? 0),
            'sugar_g'    => (int) ($data['sugar_g'] ?? 0),
            'sodium_mg'  => (int) ($data['sodium_mg'] ?? 0),
            'confidence' => (float) ($data['confidence'] ?? 0.5),
            'raw'        => $text,
        ];
    }
}
