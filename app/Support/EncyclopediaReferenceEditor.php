<?php

namespace App\Support;

use App\Models\EncyclopediaEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EncyclopediaReferenceEditor
{
    public static function save(Request $request, EncyclopediaEntry $entry, array &$uploaded, array &$retired): void
    {
        $input = $request->validate([
            'references' => ['sometimes', 'array'],
            'references.*' => ['array:id,title,type,url,pdf'],
            'references.*.id' => ['nullable', 'integer', 'distinct'],
            'references.*.title' => ['required', 'string', 'max:240'],
            'references.*.type' => ['required', 'in:web,pdf'],
            'references.*.url' => ['required_if:references.*.type,web', 'nullable', 'url:http,https', 'max:2048'],
            'references.*.pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'removed_references' => ['sometimes', 'array'],
            'removed_references.*' => ['integer'],
        ]);
        $existing = $entry->references()->lockForUpdate()->get()->keyBy('id');
        foreach (array_values($input['removed_references'] ?? []) as $id) {
            if ($reference = $existing->pull($id)) {
                if ($reference->pdf_path) $retired[] = $reference->pdf_path;
                $reference->delete();
            }
        }

        $position = 0;
        foreach ($input['references'] ?? [] as $index => $row) {
            $reference = empty($row['id']) ? $entry->references()->make() : $existing->get($row['id']);
            if (!$reference) {
                throw ValidationException::withMessages(['references' => 'This reference has changed. Reload the article and try again.']);
            }

            $path = null;
            if ($row['type'] === 'pdf') {
                $path = $reference->pdf_path;
                if ($file = $row['pdf'] ?? null) {
                    if (file_get_contents($file->getRealPath(), false, null, 0, 5) !== '%PDF-') {
                        throw ValidationException::withMessages(["references.$index.pdf" => 'Select a valid PDF document.']);
                    }
                    $path = $file->store('encyclopedia-pdfs', 'local');
                    if (!$path) {
                        throw ValidationException::withMessages(["references.$index.pdf" => 'The PDF could not be stored. Please select it again.']);
                    }
                    $uploaded[] = $path;
                }
                if (!$path) {
                    throw ValidationException::withMessages(["references.$index.pdf" => 'Choose a PDF for this reference.']);
                }
            }
            if ($reference->pdf_path && $reference->pdf_path !== $path) $retired[] = $reference->pdf_path;
            $reference->fill([
                'title' => $row['title'],
                'url' => $row['type'] === 'web' ? $row['url'] : null,
                'pdf_path' => $path,
                'position' => $position++,
            ])->save();
        }
    }
}
