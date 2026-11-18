@extends('layouts.admin')
@section('title','Site Settings')
@section('content')
    <p class="lead">Edit the homepage’s text and background image. Public educational articles and references are managed separately.</p>
    <div class="panel form-panel"><form method="POST" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data">@csrf @method('PUT')
        <label for="site_name">Website name *</label><input id="site_name" name="site_name" required maxlength="100" value="{{ old('site_name',$settings['site_name'] ?? 'LIBÉRICA') }}">
        <label for="hero_kicker">Hero eyebrow *</label><input id="hero_kicker" name="hero_kicker" required maxlength="100" value="{{ old('hero_kicker',$settings['hero_kicker'] ?? '') }}">
        <label for="hero_title">Hero heading *</label><input id="hero_title" name="hero_title" required maxlength="120" value="{{ old('hero_title',$settings['hero_title'] ?? '') }}">
        <label for="hero_accent">Gold italic heading *</label><input id="hero_accent" name="hero_accent" required maxlength="120" value="{{ old('hero_accent',$settings['hero_accent'] ?? '') }}">
        <label for="hero_description">Introduction *</label><textarea id="hero_description" name="hero_description" maxlength="700" rows="4" required>{{ old('hero_description',$settings['hero_description'] ?? '') }}</textarea>
        <label for="hero_image">Replace hero background photo (max 4 MB)</label><input id="hero_image" name="hero_image" type="file" accept="image/jpeg,image/png,image/webp"><p class="muted">If left blank, the existing hero image remains unchanged.</p>
        <label for="contact_email">Contact email (optional)</label><input id="contact_email" name="contact_email" type="email" maxlength="190" value="{{ old('contact_email',$settings['contact_email'] ?? '') }}">
        <button class="primary-button" type="submit">SAVE WEBSITE SETTINGS</button>
    </form></div>
@endsection
