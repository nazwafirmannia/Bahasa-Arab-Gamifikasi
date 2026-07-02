<?php

namespace App\Helpers;

/**
 * ArabicHelper
 * 
 * Helper class untuk processing konten pembelajaran Bahasa Arab.
 * Dipindahkan dari Blade view agar:
 * - View fokus pada rendering
 * - Logic reusable di seluruh aplikasi
 * - Mudah di-unit test
 * - Tidak dipanggil berulang saat view di-render
 * 
 * PENTING: Struktur array output TIDAK DIUBAH agar kompatibel
 * dengan Blade lama yang sudah berjalan.
 */
class ArabicHelper
{
    /* ===========================================================
       1. DETEKSI TEKS ARAB
       =========================================================== */

    /**
     * Deteksi apakah teks didominasi karakter Arab (>30%).
     * Menggunakan fallback manual untuk server tanpa mb_ord().
     *
     * @param string|null $text
     * @return bool
     */
    public static function isArabicText($text)
    {
        if (empty($text)) return false;

        // Fallback jika mb_strlen tidak tersedia
        if (!function_exists('mb_strlen')) {
            return self::isArabicFallback($text);
        }

        $len = mb_strlen($text, 'UTF-8');
        if ($len === 0) return false;

        $arabicCount = 0;

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $code = self::getCharCode($char);

            if ($code === null) continue;

            // Arabic Unicode ranges
            if (($code >= 0x0600 && $code <= 0x06FF) ||     // Arabic
                ($code >= 0x0750 && $code <= 0x077F) ||     // Arabic Supplement
                ($code >= 0x08A0 && $code <= 0x08FF) ||     // Arabic Extended
                ($code >= 0xFB50 && $code <= 0xFDFF) ||     // Arabic Presentation Forms-A
                ($code >= 0xFE70 && $code <= 0xFEFF)) {     // Arabic Presentation Forms-B
                $arabicCount++;
            }
        }

        return ($arabicCount / $len) > 0.3;
    }

    /**
     * Alias backward compatibility.
     */
    public static function containsArabic($text)
    {
        return self::isArabicText($text);
    }

    /**
     * Ambil Unicode code point dengan fallback untuk PHP lama.
     *
     * @param string $char
     * @return int|null
     */
    private static function getCharCode($char)
    {
        if (function_exists('mb_ord')) {
            $code = mb_ord($char, 'UTF-8');
            return $code !== false ? $code : null;
        }

        return self::utf8CharToCodePoint($char);
    }

    /**
     * Manual UTF-8 → code point decoder.
     * Fallback untuk server tanpa mbstring.
     *
     * @param string $char
     * @return int|null
     */
    private static function utf8CharToCodePoint($char)
    {
        $bytes = unpack('C*', $char);
        if (!$bytes) return null;

        $byteCount = count($bytes);

        if ($byteCount === 1) {
            return $bytes[1];
        } elseif ($byteCount === 2) {
            return (($bytes[1] & 0x1F) << 6) | ($bytes[2] & 0x3F);
        } elseif ($byteCount === 3) {
            return (($bytes[1] & 0x0F) << 12) |
                   (($bytes[2] & 0x3F) << 6) |
                   ($bytes[3] & 0x3F);
        } elseif ($byteCount === 4) {
            return (($bytes[1] & 0x07) << 18) |
                   (($bytes[2] & 0x3F) << 12) |
                   (($bytes[3] & 0x3F) << 6) |
                   ($bytes[4] & 0x3F);
        }

        return null;
    }

    /**
     * Fallback deteksi Arab berbasis byte pattern.
     * Digunakan jika mbstring tidak tersedia.
     *
     * @param string $text
     * @return bool
     */
    private static function isArabicFallback($text)
    {
        $bytes = unpack('C*', $text);
        if (!$bytes) return false;

        $arabicBytes = 0;
        $totalBytes  = count($bytes);

        for ($i = 1; $i <= $totalBytes; $i++) {
            $byte = $bytes[$i];
            // Arabic UTF-2 byte lead: 0xD8 / 0xD9
            if ($byte === 0xD8 || $byte === 0xD9) {
                $arabicBytes++;
            }
        }

        return $totalBytes > 0 && ($arabicBytes / $totalBytes) > 0.15;
    }

    /* ===========================================================
       2. PARSER VOCABULARY
       Format input: arabic = translit = meaning = emoji
       Group separator: ===GroupName===
       
       OUTPUT (WAJIB SAMA dengan Blade lama):
       [
         'GroupName' => [
           ['group'=>'...', 'arabic'=>'...', 'translit'=>'...', 'meaning'=>'...', 'emoji'=>'...'],
           ...
         ]
       ]
       =========================================================== */

    /**
     * Parse konten vocab mentah menjadi array terstruktur per grup.
     * Menggunakan limit explode=4 agar arti yang mengandung "=" tidak rusak.
     *
     * @param string|null $vocabRaw
     * @return array
     */
    public static function parseVocab($vocabRaw)
    {
        if (empty($vocabRaw)) return [];

        $vocabWords   = [];
        $currentGroup = 'Umum';

        // Normalisasi line breaks
        $vocabRaw = str_replace(
            ['<br>', '<br/>', '</li>', '<li>', '<ul>', '</ul>'],
            "\n",
            $vocabRaw
        );
        $vocabRaw = strip_tags($vocabRaw);

        foreach (explode("\n", $vocabRaw) as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Group separator
            if (strpos($line, '===') !== false) {
                $groupName = trim(str_replace('=', '', $line));
                $currentGroup = $groupName !== '' ? $groupName : 'Umum';
                continue;
            }

            // Vocab line
            if (strpos($line, '=') !== false) {
                // LIMIT 4 → cegah arti "x = y" berantakan
                $parts = array_map('trim', explode('=', $line, 4));
                if (count($parts) >= 2) {
                    $vocabWords[] = [
                        'group'    => $currentGroup,
                        'arabic'   => $parts[0],
                        'translit' => $parts[1] ?? '',
                        'meaning'  => $parts[2] ?? '',
                        'emoji'    => $parts[3] ?? ''
                    ];
                }
            }
        }

        // Group by category
        $groupedVocab = [];
        foreach ($vocabWords as $word) {
            $groupedVocab[$word['group']][] = $word;
        }

        return $groupedVocab;
    }

    /* ===========================================================
       3. PARSER GRAMMAR
       Mendukung: heading, bullet, numbered, table markdown,
                  contoh/example, paragraf Arab RTL, HTML mentah
       =========================================================== */

    /**
     * Parse konten grammar mentah menjadi HTML.
     *
     * @param string|null $text
     * @return string HTML
     */
    public static function parseGrammar($text)
    {
        if (empty(trim($text ?? ''))) {
            return '<div class="material-empty">
                <div class="material-empty__icon">📖</div>
                <p class="material-empty__text material-empty__text--italic">Konten grammar belum tersedia.</p>
            </div>';
        }

        // Jika sudah HTML → sanitasi lalu bungkus
        if (strpos($text, '<') !== false && strpos($text, '>') !== false) {
            return '<div class="material-grammar-card material-grammar-card--html">' .
                   self::sanitizeHtml($text) .
                   '</div>';
        }

        $lines = explode("\n", $text);
        $html  = '';
        $inList    = false;
        $inExample = false;
        $inTable   = false;

        foreach ($lines as $line) {
            $originalLine = $line;
            $line = trim($line);

            // Baris kosong → tutup block terbuka
            if (empty($line)) {
                if ($inList)    { $html .= '</ul>';                $inList    = false; }
                if ($inExample) { $html .= '</div></div>';         $inExample = false; }
                continue;
            }

            /* ---------- TABLE MARKDOWN ---------- */
            if (str_starts_with($line, '|') && str_ends_with($line, '|')) {
                $cells = array_map('trim', explode('|', trim($line, '|')));
                // Skip separator row (---|---|---)
                if (preg_match('/^[\-\s|]+$/', $line)) continue;

                if (!$inTable) {
                    $html .= '<div class="material-grammar-table-wrap"><table class="material-grammar-table">';
                    $inTable = 'header';
                }

                if ($inTable === 'header') {
                    $html .= '<thead><tr>';
                    foreach ($cells as $cell) {
                        $isArabic = self::isArabicText($cell);
                        $cls = $isArabic ? ' class="material-grammar-table__arabic"' : '';
                        $html .= '<th' . $cls . '>' .
                                 htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</th>';
                    }
                    $html .= '</tr></thead><tbody>';
                    $inTable = 'body';
                } else {
                    $html .= '<tr>';
                    foreach ($cells as $cell) {
                        $isArabic = self::isArabicText($cell);
                        $cls = $isArabic ? ' class="material-grammar-table__arabic"' : '';
                        $html .= '<td' . $cls . '>' .
                                 htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
                    }
                    $html .= '</tr>';
                }
                continue;
            } elseif ($inTable) {
                $html .= '</tbody></table></div>';
                $inTable = false;
            }

            /* ---------- HEADING ---------- */
            if (str_starts_with($line, '# ') || str_starts_with($line, '## ')) {
                $title = ltrim($line, '# ');
                $html .= '<h3 class="material-grammar-heading">' .
                         htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>';
                continue;
            }

            /* ---------- BULLET LIST ---------- */
            if (str_starts_with($line, '- ') ||
                str_starts_with($line, '* ') ||
                str_starts_with($line, '• ')) {

                if (!$inList) {
                    $html .= '<ul class="material-grammar-list">';
                    $inList = true;
                }
                $content = substr($line, 2);

                // Pair: arabic = meaning
                if (strpos($content, ' = ') !== false) {
                    $parts   = explode(' = ', $content, 2);
                    $arabic  = trim($parts[0]);
                    $meaning = trim(end($parts));
                    $isArabic = self::isArabicText($arabic);

                    $cls = $isArabic
                        ? 'material-grammar-list__arabic'
                        : 'material-grammar-list__bold';
                    $dir = $isArabic ? ' dir="rtl"' : '';

                    $html .= '<li class="material-grammar-list__item material-grammar-list__item--pair">'
                        . '<span class="' . $cls . '"' . $dir . '>'
                        . htmlspecialchars($arabic, ENT_QUOTES, 'UTF-8') . '</span>'
                        . '<span class="material-grammar-list__meaning">= '
                        . htmlspecialchars($meaning, ENT_QUOTES, 'UTF-8') . '</span>'
                        . '</li>';
                } else {
                    $html .= '<li class="material-grammar-list__item">'
                        . '<span class="material-grammar-list__bullet">•</span>'
                        . '<span>' . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . '</span>'
                        . '</li>';
                }
                continue;
            } elseif ($inList &&
                      !str_starts_with($originalLine, '- ') &&
                      !str_starts_with($originalLine, '* ') &&
                      !str_starts_with($originalLine, '• ')) {
                $html .= '</ul>';
                $inList = false;
            }

            /* ---------- NUMBERED LIST ---------- */
            if (preg_match('/^(\d+)\.\s+(.+)$/', $line, $m)) {
                $html .= '<div class="material-grammar-numbered">'
                    . '<span class="material-grammar-numbered__num">' . (int)$m[1] . '</span>'
                    . '<span class="material-grammar-numbered__text">'
                    . htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8') . '</span>'
                    . '</div>';
                continue;
            }

            /* ---------- CONTOH / EXAMPLE ---------- */
            if (stripos($line, 'contoh:') === 0 || stripos($line, 'example:') === 0) {
                $html .= '<div class="material-grammar-example">'
                    . '<p class="material-grammar-example__label">✨ Contoh:</p>'
                    . '<div class="material-grammar-example__content">';
                $inExample = true;
                continue;
            }

            /* ---------- FULL ARABIC LINE ---------- */
            if (self::isArabicText($line)) {
                $html .= '<p class="material-grammar-arabic" dir="rtl">'
                    . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</p>';
                continue;
            }

            /* ---------- DEFAULT: PARAGRAPH ---------- */
            $html .= '<p class="material-grammar-paragraph">'
                . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</p>';
        }

        // Tutup block yang masih terbuka
        if ($inList)    $html .= '</ul>';
        if ($inExample) $html .= '</div></div>';
        if ($inTable)   $html .= '</tbody></table></div>';

        return $html;
    }

    /* ===========================================================
       4. PARSER DIALOG
       Mendukung 3 format:
         - advanced  : [CHARACTER:...] [ACTION:...] === SCENE:
         - simple    : Nama: teks Arab (terjemahan)
         - fallback  : baris Arab bergantian dengan terjemahan
       
       OUTPUT (WAJIB SAMA dengan Blade lama):
       [
         'scene'     => string,
         'time'      => string,
         'dialogues' => [
           ['name'=>..., 'avatar'=>..., 'arabic'=>..., 'translation'=>...,
            'mood'=>..., 'isRight'=>bool, 'action'=>...],
           ...
         ]
       ]
       =========================================================== */

    /**
     * Parse konten dialog mentah.
     *
     * @param string|null $dialogRaw
     * @return array
     */
    public static function parseDialog($dialogRaw)
    {
        $scene     = '';
        $time      = '';
        $dialogues = [];

        if (empty($dialogRaw)) {
            return ['scene' => '', 'time' => '', 'dialogues' => []];
        }

        $isAdvancedFormat = str_contains($dialogRaw, '[CHARACTER:') ||
                            str_contains($dialogRaw, '=== SCENE:');

        // Deteksi simple format
        $isSimpleFormat = false;
        if (!$isAdvancedFormat) {
            foreach (explode("\n", $dialogRaw) as $testLine) {
                $testLine = trim($testLine);
                if (empty($testLine)) continue;
                if (preg_match('/^[A-Za-z\s]+:\s*.+$/u', $testLine)) {
                    $parts = explode(':', $testLine, 2);
                    if (count($parts) === 2 && self::isArabicText(trim($parts[1]))) {
                        $isSimpleFormat = true;
                        break;
                    }
                }
            }
        }

        /* ---------- ADVANCED FORMAT ---------- */
        if ($isAdvancedFormat) {
            $currentChar = null;
            foreach (explode("\n", $dialogRaw) as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                if (str_starts_with($line, '=== SCENE:')) {
                    $scene = trim(str_replace('=== SCENE:', '', $line));
                } elseif (str_starts_with($line, '[BACKGROUND:')) {
                    // background disimpan tapi tidak dipakai di output lama
                    trim(str_replace(['[BACKGROUND:', ']'], '', $line));
                } elseif (str_starts_with($line, '[TIME:')) {
                    $time = trim(str_replace(['[TIME:', ']'], '', $line));
                } elseif (str_starts_with($line, '[CHARACTER:')) {
                    $meta  = trim(str_replace(['[CHARACTER:', ']'], '', $line));
                    $parts = array_map('trim', explode('|', $meta));
                    $currentChar = ['name' => '', 'avatar' => '👤', 'mood' => ''];
                    foreach ($parts as $p) {
                        if (str_starts_with($p, 'AVATAR:')) {
                            $currentChar['avatar'] = trim(str_replace('AVATAR:', '', $p));
                        } elseif (str_starts_with($p, 'MOOD:')) {
                            $currentChar['mood'] = trim(str_replace('MOOD:', '', $p));
                        } elseif (!str_contains($p, ':')) {
                            $currentChar['name'] = $p;
                        }
                    }
                } elseif (str_starts_with($line, '[ACTION:') && $currentChar) {
                    $currentChar['action'] = trim(str_replace(['[ACTION:', ']'], '', $line));
                } elseif ($currentChar && !str_starts_with($line, '(') && !isset($currentChar['arabic'])) {
                    $currentChar['arabic'] = $line;
                } elseif ($currentChar && str_starts_with($line, '(') && isset($currentChar['arabic'])) {
                    $currentChar['translation'] = trim($line, '()');
                    $dialogues[] = $currentChar;
                    $currentChar = null;
                }
            }

        /* ---------- SIMPLE FORMAT ---------- */
        } elseif ($isSimpleFormat) {
            $avatars = [
                'ahmad'   => '👦', 'fatimah' => '👧', 'zaid'    => '👨',
                'aisyah'  => '👩', 'hasan'   => '🧑', 'ustadz'  => '👨‍',
                'guru'    => '👨‍🏫'
            ];
            $isRightNames = ['ahmad', 'zaid', 'hasan', 'ustadz', 'guru'];

            foreach (explode("\n", $dialogRaw) as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                if (preg_match('/^([A-Za-z\s]+):\s*(.+)$/u', $line, $m)) {
                    $name    = trim($m[1]);
                    $content = trim($m[2]);

                    $arabic      = $content;
                    $translation = '';
                    if (preg_match('/^(.+?)\s*\(([^)]+)\)\s*$/', $content, $tm)) {
                        $arabic      = trim($tm[1]);
                        $translation = trim($tm[2]);
                    }

                    $dialogues[] = [
                        'name'        => $name,
                        'avatar'      => $avatars[strtolower($name)] ?? '👤',
                        'arabic'      => $arabic,
                        'translation' => $translation,
                        'mood'        => '',
                        'isRight'     => in_array(strtolower($name), $isRightNames)
                    ];
                }
            }

        /* ---------- FALLBACK FORMAT ---------- */
        } else {
            $lines = array_filter(array_map('trim', explode("\n", $dialogRaw)));
            $charIndex     = 0;
            $currentArabic = null;

            foreach ($lines as $line) {
                if (self::isArabicText($line)) {
                    $currentArabic = $line;
                } elseif ($currentArabic !== null) {
                    $dialogues[] = [
                        'name'        => 'Karakter ' . chr(65 + ($charIndex % 2)),
                        'avatar'      => $charIndex % 2 === 0 ? '👦' : '👧',
                        'arabic'      => $currentArabic,
                        'translation' => $line,
                        'mood'        => '',
                        'isRight'     => $charIndex % 2 === 1
                    ];
                    $currentArabic = null;
                    $charIndex++;
                }
            }
        }

        return [
            'scene'     => $scene,
            'time'      => $time,
            'dialogues' => $dialogues
        ];
    }

    /* ===========================================================
       5. SANITASI HTML (ANTI-XSS)
       =========================================================== */

    /**
     * Sanitasi HTML mentah dari admin untuk mencegah XSS.
     * Menghapus: <script>, event handler, javascript: URL,
     *            <iframe>, <object>, <embed>, <form>, <input>, <button>.
     *
     * @param string $html
     * @return string
     */
    public static function sanitizeHtml($html)
    {
        // 1. Hapus <script>...</script>
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);

        // 2. Hapus event handler (onclick, onerror, onload, dll)
        $html = preg_replace('#\s+on\w+\s*=\s*["\'][^"\']*["\']#i', '', $html);
        $html = preg_replace('#\s+on\w+\s*=\s*[^\s>]+#i', '', $html);

        // 3. Hapus javascript: URL
        $html = preg_replace('#href\s*=\s*["\']?\s*javascript:[^"\'>]+["\']?#i', 'href="#"', $html);
        $html = preg_replace('#action\s*=\s*["\']?\s*javascript:[^"\'>]+["\']?#i', 'action="#"', $html);

        // 4. Hapus tag berbahaya
        $dangerous = ['iframe', 'object', 'embed', 'form', 'input', 'button', 'meta', 'link'];
        foreach ($dangerous as $tag) {
            $html = preg_replace('#<' . $tag . '(.*?)>(.*?)</' . $tag . '>#is', '', $html);
            $html = preg_replace('#<' . $tag . '[^>]*/?>#is', '', $html);
        }

        // 5. Hapus CSS berbahaya (expression, url javascript)
        $html = preg_replace('#expression\s*\(#i', '', $html);
        $html = preg_replace('#url\s*\(\s*["\']?\s*javascript:#i', '', $html);

        // 6. Hapus data: URI yang bisa dipakai XSS
        $html = preg_replace('#(href|src)\s*=\s*["\']?\s*data:[^"\'>]+["\']?#i', '$1="#"', $html);

        return $html;
    }

    /* ===========================================================
       6. HITUNG PROGRESS MATERI
       Hanya menggunakan field yang SUDAH ADA di database:
         - UserMaterialProgress.is_selesai
       TIDAK menambah field baru (vocab_read, grammar_read, dll).
       =========================================================== */

    /**
     * Hitung progress materi berdasarkan data yang sudah ada.
     *
     * Logika:
     *   - is_selesai = true  → 100%
     *   - is_selesai = false → 0%
     *
     * Jika di masa depan ada field tambahan (misal vocab_read),
     * method ini bisa di-upgrade tanpa mengubah Blade.
     *
     * @param object|null $progress (UserMaterialProgress)
     * @return int 0-100
     */
    public static function calculateProgress($progress)
    {
        if (!$progress) return 0;

        return !empty($progress->is_selesai) ? 100 : 0;
    }
}