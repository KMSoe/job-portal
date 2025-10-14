<?php
namespace Modules\Recruitment\App\Services;

class PdfResumeParserService
{
    public function parse(string $pdfPath, $jobPosting): array
    {
        $text  = $this->extractText($pdfPath);
        $text  = $this->normalize($text);
        $lines = array_filter(array_map('trim', explode("\n", $text)));

        $jobPostingSkills = $jobPosting->skills->pluck('name')->toArray();
        return [
            'full_name'      => $this->extractName($lines),
            'email'          => $this->extractEmail($text),
            'phone'          => $this->extractPhone($text),
            'matched_skills' => $this->extractSkills($jobPostingSkills, $text),
            'education'      => $this->extractEducation($lines),
            'experience'     => $this->extractExperience($lines),
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

    protected function extractSkills($jobPostingSkills, string $text): array
    {
        $found = [];
        $lower = strtolower($text);

        foreach ($jobPostingSkills as $skill) {
            if (stripos($lower, $skill) !== false) {
                $found[] = ucfirst($skill);
            }
        }
        return array_unique($found);
    }

    protected function extractEducation(array $lines): array
    {
        $edu      = [];
        $keywords = '/(education|certificate|certified|bachelor|master|mba|phd|doctoral|associate|degree|diploma|graduation|graduated|university|college|institute|academy|school|major|minor|alumni|course|gpa)/i';
        foreach ($lines as $line) {
            if (preg_match($keywords, $line)) {
                if ($line !== 'education') {
                    $edu[] = $line;
                }
            }
        }
        return $edu;
    }

    protected function extractExperience(array $lines): array
    {
        $exp = [];

        // Expanded list of keywords for section headers, job roles, action verbs, and organizations
        $keywords = '/(experience|employment|work history|professional|career|developer|engineer|manager|director|analyst|specialist|coordinator|lead|company|organization|firm|employer|worked|employed|job|title|project|accomplishment|responsibility|achieved|implemented|developed|contributed)/i';

        foreach ($lines as $line) {
            if (preg_match($keywords, $line)) {
                if ($line !== 'experience') {
                    $exp[] = $line;
                }
            }
        }
        return $exp;
    }
}
