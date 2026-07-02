<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

/**
 * @method string getText()
 */
class MaterialParser
{
    protected PdfParser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
    }

    /**
     * Parse file (PDF atau Word) dan ekstrak konten
     */
    public function parseFile(string $filePath, string $fileType): array
    {
        $text = '';

        // Ekstrak teks dari file
        if ($fileType === 'pdf') {
            $text = $this->parsePDF($filePath);
        } elseif ($fileType === 'word') {
            $text = $this->parseWord($filePath);
        }

        // Parse teks menjadi structured data
        return $this->parseContent($text);
    }

    /**
     * Parse PDF
     */
    private function parsePDF(string $filePath): string
    {
        $pdf = $this->pdfParser->parseFile($filePath);
        return $pdf->getText() ?? '';
    }

    /**
     * Parse Word (.docx) - dengan safe method call
     */
    private function parseWord(string $filePath): string
    {
        $phpWord = IOFactory::load($filePath);
        $text = '';
        
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // Safe call: cek method exists dulu
                if (method_exists($element, 'getText')) {
                    $content = $element->getText();
                    if (is_string($content)) {
                        $text .= $content . "\n";
                    }
                }
                // Handle TextRun yang punya getElements()
                elseif (method_exists($element, 'getElements')) {
                    foreach ($element->getElements() as $subElement) {
                        if (method_exists($subElement, 'getText')) {
                            $content = $subElement->getText();
                            if (is_string($content)) {
                                $text .= $content . "\n";
                            }
                        }
                    }
                }
            }
        }
        
        return $text;
    }

    /**
     * Parse konten menjadi struktur vocab, grammar, dialog
     */
    private function parseContent(string $text): array
    {
        $data = [
            'vocab_content' => '',
            'grammar_content' => '',
            'dialog_content' => '',
        ];

        $lines = explode("\n", $text);
        $currentSection = '';
        $vocabLines = [];
        $grammarLines = [];
        $dialogLines = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Deteksi section
            if (stripos($line, '=== VOCAB') !== false || stripos($line, '=== KOSAKATA') !== false) {
                $currentSection = 'vocab';
                continue;
            } elseif (stripos($line, '=== GRAMMAR') !== false || stripos($line, '=== TATA BAHASA') !== false) {
                $currentSection = 'grammar';
                continue;
            } elseif (stripos($line, '=== DIALOG') !== false || stripos($line, '=== PERCAKAPAN') !== false) {
                $currentSection = 'dialog';
                continue;
            }

            // Masukkan ke section yang sesuai
            switch ($currentSection) {
                case 'vocab':
                    $vocabLines[] = $line;
                    break;
                case 'grammar':
                    $grammarLines[] = $line;
                    break;
                case 'dialog':
                    $dialogLines[] = $line;
                    break;
            }
        }

        // Format ulang konten
        $data['vocab_content'] = $this->formatVocab($vocabLines);
        $data['grammar_content'] = $this->formatGrammar($grammarLines);
        $data['dialog_content'] = $this->formatDialog($dialogLines);

        return $data;
    }

    /**
     * Format vocab agar sesuai dengan parser blade
     */
    private function formatVocab(array $lines): string
    {
        $formatted = "=== KATA DASAR ===\n";
        $category = 'Umum';

        foreach ($lines as $line) {
            // Deteksi kategori
            if (strpos($line, '===') !== false) {
                $category = trim(str_replace('=', '', $line));
                $formatted .= "\n=== {$category} ===\n";
                continue;
            }

            // Parse format: Arab = Arti atau Arab = Translit = Arti
            if (strpos($line, '=') !== false) {
                $parts = array_map('trim', explode('=', $line));
                
                if (count($parts) >= 2) {
                    $arabic = $parts[0];
                    $translit = count($parts) >= 3 ? $parts[1] : '';
                    $meaning = $parts[count($parts) - 1]; // Ambil yang terakhir
                    
                    // Tambah emoji default jika belum ada
                    $emoji = $this->detectEmoji($meaning);
                    
                    $formatted .= "{$arabic} = {$translit} = {$meaning} = {$emoji}\n";
                }
            }
        }

        return $formatted;
    }

    /**
     * Format grammar
     */
    private function formatGrammar(array $lines): string
    {
        return implode("\n", $lines);
    }

    /**
     * Format dialog
     */
    private function formatDialog(array $lines): string
    {
        $formatted = "=== SCENE: Dialog ===\n";
        $formatted .= "[BACKGROUND: classroom]\n\n";

        foreach ($lines as $line) {
            // Jika ada format "Nama: teks", konversi ke format character
            if (preg_match('/^([A-Za-z\s]+):\s*(.+)$/', $line, $matches)) {
                $name = trim($matches[1]);
                $text = trim($matches[2]);
                
                // Deteksi avatar berdasarkan nama
                $avatar = $this->detectAvatar($name);
                
                $formatted .= "[CHARACTER: {$name} | AVATAR: {$avatar}]\n";
                $formatted .= "{$text}\n";
                
                // Jika ada terjemahan dalam kurung
                if (preg_match('/\((.+)\)/', $text, $transMatches)) {
                    $formatted .= "({$transMatches[1]})\n";
                }
                
                $formatted .= "\n";
            } else {
                $formatted .= "{$line}\n";
            }
        }

        return $formatted;
    }

    /**
     * Deteksi emoji berdasarkan arti
     */
    private function detectEmoji(string $meaning): string
    {
        $meaning = strtolower($meaning);
        
        if (str_contains($meaning, 'buku')) return '📚';
        if (str_contains($meaning, 'pena') || str_contains($meaning, 'pulpen')) return '✏️';
        if (str_contains($meaning, 'sekolah')) return '🏫';
        if (str_contains($meaning, 'guru')) return '👨‍🏫';
        if (str_contains($meaning, 'siswa') || str_contains($meaning, 'murid')) return '🎒';
        if (str_contains($meaning, 'nama')) return '🏷️';
        if (str_contains($meaning, 'jendela')) return '🪟';
        if (str_contains($meaning, 'tas')) return '👜';
        if (str_contains($meaning, 'jam')) return '⌚';
        
        return '📝'; // Default
    }

    /**
     * Deteksi avatar berdasarkan nama
     */
    private function detectAvatar(string $name): string
    {
        $name = strtolower($name);
        
        if (str_contains($name, 'ustadz') || str_contains($name, 'guru')) return '👨‍🏫';
        if (str_contains($name, 'ahmad') || str_contains($name, 'zaid')) return '👦';
        if (str_contains($name, 'siti') || str_contains($name, 'fatimah')) return '👧';
        
        return '👤'; // Default
    }
}