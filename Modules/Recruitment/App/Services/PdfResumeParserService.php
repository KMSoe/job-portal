<?php
namespace Modules\Recruitment\App\Services;

use Spatie\PdfToText\Pdf;

class PdfResumeParserService
{
    protected array $skillsList = [
        'php', 'laravel', 'javascript', 'react', 'node', 'vue', 'mysql', 'postgres', 'aws',
        'docker', 'kubernetes', 'python', 'java', 'c#', 'git', 'html', 'css', 'redis', 'mongodb',
        'rest', 'graphql', 'typescript', 'go',
    ];

    public function parse(string $pdfPath): array
    {
        $text  = $this->extractText($pdfPath);
        $text  = $this->normalize($text);
        $lines = array_filter(array_map('trim', explode("\n", $text)));

        return [
            'full_name'  => $this->extractName($lines),
            'email'      => $this->extractEmail($text),
            'phone'      => $this->extractPhone($text),
            'skills'     => $this->extractSkills($text),
            'education'  => $this->extractEducation($lines),
            'experience' => $this->extractExperience($lines),
            // 'raw_text'   => $text,
        ];
    }

    protected function extractText(string $path): string
    {
        // return trim(Pdf::getText($path));
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($path);

        $rawText = $pdf->getText();
        return trim($rawText);
    }

    protected function normalize(string $text): string
    {
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        return trim(preg_replace("/[ \t]+/", " ", $text));
    }

    protected function extractEmail(string $text): ?string
    {
        return preg_match('/[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}/i', $text, $m) ? $m[0] : null;
    }

    protected function extractPhone(string $text): ?string
    {
        if (preg_match('/(\+?\d{1,3}[-.\s]?)?\(?\d{2,4}\)?[-.\s]?\d{3,4}[-.\s]?\d{3,4}/', $text, $m)) {
            return preg_replace('/[^\d\+]/', '', $m[0]);
        }
        return null;
    }

    protected function extractName(array $lines): ?string
    {
        $first = trim($lines[0] ?? '');
        if ($first && strlen($first) < 60 && ! preg_match('/(resume|curriculum vitae|cv)/i', $first)) {
            return $first;
        }

        foreach (array_slice($lines, 0, 10) as $ln) {
            if (preg_match('/^[A-Z][a-z]+\s+[A-Z][a-z]+/', $ln)) {
                return trim($ln);
            }
        }

        return null;
    }

    protected function extractSkills(string $text): array
    {
        $found = [];
        $lower = strtolower($text);
        foreach ($this->skillsList as $skill) {
            if (strpos($lower, $skill) !== false) {
                $found[] = ucfirst($skill);
            }
        }
        return array_unique($found);
    }

    protected function extractEducation(array $lines): array
    {
        $edu = [];
        foreach ($lines as $line) {
            if (preg_match('/(bachelor|master|mba|phd|university|college|institute)/i', $line)) {
                $edu[] = $line;
            }
        }
        return $edu;
    }

    protected function extractExperience(array $lines): array
    {
        $exp = [];
        foreach ($lines as $line) {
            if (preg_match('/(experience|developer|engineer|manager|company|worked|project)/i', $line)) {
                $exp[] = $line;
            }
        }
        return $exp;
    }
}
