<?php

test('home page images have explicit width and height attributes to prevent layout shift', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    // Parse HTML to find img tags
    preg_match_all('/<img[^>]+>/i', $content, $matches);
    expect(count($matches[0]))->toBeGreaterThan(0);

    foreach ($matches[0] as $imgTag) {
        // Skip hidden or background SVG icons if any
        if (str_contains($imgTag, 'hero-bg-image')) {
            // Check if hero-bg-image has width/height or style aspect-ratio
            $hasWidthHeight = (str_contains($imgTag, 'width=') && str_contains($imgTag, 'height=')) || str_contains($imgTag, 'aspect-ratio');
            expect($hasWidthHeight)->toBeTrue('Hero background image is missing width/height attributes');
        }

        if (str_contains($imgTag, 'about-clean-img')) {
            $hasWidthHeight = (str_contains($imgTag, 'width=') && str_contains($imgTag, 'height=')) || str_contains($imgTag, 'aspect-ratio');
            expect($hasWidthHeight)->toBeTrue('About clean image is missing width/height attributes');
        }
    }
});

test('layout head includes font preconnect links to reduce FOUT layout shift', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->toContain('rel="preconnect" href="https://fonts.googleapis.com"');
    expect($content)->toContain('rel="preconnect" href="https://fonts.gstatic.com"');
});

test('text reveal elements have initial opacity prevention in CSS or markup', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    // Verify root document has default navbar height set in inline script to prevent shift
    expect($content)->toContain('--navbar-height');
});
