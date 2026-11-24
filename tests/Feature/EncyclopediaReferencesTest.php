<?php

use App\Models\EncyclopediaEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class EncyclopediaReferencesTest extends TestCase
{
    public function test_editor_preserves_references_and_publication_controls_document_access(): void
    {
        $this->app->instance('env', 'testing');
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'session.driver' => 'array',
            'cache.default' => 'array',
        ]);
        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
        Storage::fake('local');
        $entry = EncyclopediaEntry::where('featured_in_discover', true)->sole();
        $topic = $entry->topic;
        $read = route('encyclopedia.entry', [$topic->slug, $entry->slug]);
        $edit = route('admin.encyclopedia.entries.update', [$topic, $entry]);
        $this->get('/')->assertOk()->assertDontSee($read);
        $this->get($read)->assertNotFound();
        $this->actingAs(User::create(['name' => 'Editor', 'email' => 'editor@example.test', 'password' => 'test-password', 'is_admin' => true]));
        $this->get(route('admin.encyclopedia.entries.edit', [$topic, $entry]))->assertOk()->assertSee('Add reference');
        $data = $entry->only(['title', 'slug', 'summary', 'body']) + ['is_published' => 1];
        $rows = $entry->references->map(fn ($ref) => $ref->only(['id', 'title', 'url']) + ['type' => 'web'])->all();
        self::assertCount(3, $rows);
        $pdf = fn () => UploadedFile::fake()->createWithContent('field-guide.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF");
        $rows[] = ['title' => 'Field guide', 'type' => 'pdf', 'pdf' => $pdf()];
        $this->put($edit, $data + ['references' => $rows])->assertSessionHasNoErrors();
        $ref = $entry->references()->where('title', 'Field guide')->sole();
        $document = route('encyclopedia.reference.pdf', $ref);
        $this->get('/')->assertOk()->assertSee($read);
        $this->get(route('encyclopedia.topic', $topic->slug))->assertSee($read);
        $this->get($read)->assertOk()->assertSee('Field guide (PDF)')->assertSee($document);
        $this->get($document)->assertOk()->assertHeader('Content-Type', 'application/pdf');

        $rows[3] = ['id' => $ref->id, 'type' => 'pdf', 'title' => 'Updated guide'];
        $oldPath = $ref->pdf_path;
        $this->put($edit, $data + ['references' => $rows])->assertSessionHasNoErrors();
        self::assertSame($oldPath, $ref->refresh()->pdf_path);
        foreach ([UploadedFile::fake()->createWithContent('bad.pdf', 'plain text'), $pdf()->size(10241)] as $bad) {
            $rows[3]['pdf'] = $bad;
            $this->put($edit, ['title' => 'Invalid change'] + $data + ['references' => $rows])->assertSessionHasErrors('references.3.pdf');
            self::assertSame($data['title'], $entry->refresh()->title);
            Storage::disk('local')->assertExists($oldPath);
        }
        $rows[3]['pdf'] = $pdf();
        $this->put($edit, $data + ['references' => $rows])->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing($oldPath);
        $replacement = $ref->refresh()->pdf_path;
        Storage::disk('local')->assertExists($replacement);

        $topic->update(['is_published' => false]);
        $this->get($document)->assertNotFound();
        $this->get('/')->assertDontSee($read);
        $topic->update(['is_published' => true]);
        $entry->update(['is_published' => false]);
        $this->get($document)->assertNotFound();
        $this->get('/')->assertDontSee($read);

        array_pop($rows);
        $this->put($edit, $data + ['references' => $rows, 'removed_references' => [$ref->id]])->assertSessionHasNoErrors();
        $this->get($document)->assertNotFound();
        Storage::disk('local')->assertMissing($replacement);
    }
}
