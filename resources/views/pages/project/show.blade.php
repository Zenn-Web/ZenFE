@extends('layout.welcome')

@section('content')

@php
    $cleanCategory = str_replace("\x95", '&#8226;', str_replace("\x96", '&#8211;', $project->category ?? ''));
    $cleanTitle = str_replace(["\x95", "\x96"], ['&#8226;', '&#8211;'], $project->title ?? '');
    $shortCategory = preg_replace('/[^A-Za-z0-9\/\+].*/', '', $project->category ?? '') ?: 'Project';
@endphp

{{-- ============================================ --}}
{{-- OVERVIEW — Header + Image + Description + Tech --}}
{{-- ============================================ --}}
<section class="dp-overview" id="dp-overview">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Compact Project Header --}}
                <div class="dp-header">
                    <a href="{{ url('/#resources') }}" class="dp-back" data-dp-anim="fade-up">
                        <i class="bi bi-arrow-left me-2"></i>
                        <span data-i18n="project.back">Kembali ke Portfolio</span>
                    </a>

                    <div class="dp-header__meta" data-dp-anim="fade-up">
                        <span class="dp-badge dp-badge--cat" data-i18n="project.category.{{ $project->slug }}">{!! $cleanCategory !!}</span>
                        <span class="dp-badge dp-badge--year">{{ $project->year }}</span>
                    </div>

                    <h1 class="dp-header__title" data-dp-anim="title-reveal" data-i18n="project.title.{{ $project->slug }}">{!! $cleanTitle !!}</h1>
                </div>

                <div class="dp-overview__grid">

                    {{-- LEFT: Project Image --}}
                    <div class="dp-overview__image-col" data-dp-anim="slide-right">
                        <div class="dp-overview__image-frame">
                            <img src="{{ asset($project->image) }}"
                                 alt="{{ $cleanTitle }}"
                                 class="dp-overview__img"
                                 loading="lazy">
                        </div>
                    </div>

                    {{-- RIGHT: Description + Tech + Quick Stats --}}
                    <div class="dp-overview__content-col">

                        {{-- About --}}
                        <div class="dp-overview__about" data-dp-anim="fade-up">
                            <span class="dp-eyebrow" data-i18n="project.about">Tentang Proyek</span>
                            <p class="dp-overview__desc"
                               data-i18n="project.description.{{ $project->slug }}">{{ $project->description }}</p>
                        </div>

                        {{-- Quick Stats --}}
                        <div class="dp-overview__stats" data-dp-anim="fade-up">
                            <div class="dp-stat">
                                <span class="dp-stat__label">Kategori</span>
                                <span class="dp-stat__value">{{ $shortCategory }}</span>
                            </div>
                            <div class="dp-stat">
                                <span class="dp-stat__label">Tahun</span>
                                <span class="dp-stat__value">{{ $project->year }}</span>
                            </div>
                            <div class="dp-stat">
                                <span class="dp-stat__label">Tech</span>
                                <span class="dp-stat__value">{{ $project->tech_stack_badges ? count($project->tech_stack_badges) . ' tools' : '-' }}</span>
                            </div>
                        </div>

                        {{-- Tech Stack --}}
                        @if($project->tech_stack_badges)
                        <div class="dp-overview__tech" data-dp-anim="fade-up">
                            <span class="dp-eyebrow" data-i18n="project.tech_stack">Tech Stack</span>
                            <div class="dp-tech-list">
                                @foreach($project->tech_stack_badges as $badge)
                                    <span class="dp-tech-pill" data-dp-anim="badge-pop">{{ $badge["name"] }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- NARRATIVE — Flow / Process --}}
{{-- ============================================ --}}
@if($project->flow_description)
<section class="dp-narrative" id="dp-narrative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="dp-narrative__header" data-dp-anim="fade-up">
                    <span class="dp-eyebrow" data-i18n="project.flow">Alur / Flow Description</span>
                </div>

                <div class="dp-narrative__body">
                    {{-- Animated vertical timeline --}}
                    <div class="dp-narrative__timeline" aria-hidden="true">
                        <div class="dp-narrative__timeline-track"></div>
                        <div class="dp-narrative__timeline-dot"></div>
                    </div>

                    <div class="dp-narrative__text" data-dp-anim="fade-up" data-i18n="project.flow_description.{{ $project->slug }}">
                        {!! nl2br(e($project->flow_description)) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================ --}}
{{-- CTA — Call to Action --}}
{{-- ============================================ --}}
<section class="dp-cta" id="dp-cta">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="dp-cta__wrap" data-dp-anim="fade-up">
                    @if($project->live_demo_url)
                        <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener" class="dp-cta__btn dp-cta__btn--primary">
                            <span data-i18n="project.live_demo">Live Demo</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    @endif

                    @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="dp-cta__btn dp-cta__btn--secondary">
                            <i class="bi bi-github"></i>
                            <span data-i18n="project.github">Lihat di GitHub</span>
                        </a>
                    @else
                        <div class="dp-cta__btn dp-cta__btn--locked" title="Repository ini bersifat privat untuk menjaga kerahasiaan data agensi/klien.">
                            <i class="bi bi-lock-fill"></i>
                            <span data-i18n="project.private_repo">Repositori Privat</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    @vite(['resources/js/detail-animations.js'])
@endpush
