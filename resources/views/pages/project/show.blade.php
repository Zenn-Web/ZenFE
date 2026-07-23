@extends('layout.welcome')

@section('content')

@php
    $cleanCategory = str_replace("\x95", '&#8226;', str_replace("\x96", '&#8211;', $project->category ?? ''));
    $cleanTitle = str_replace(["\x95", "\x96"], ['&#8226;', '&#8211;'], $project->title ?? '');
    $shortCategory = preg_replace('/[^A-Za-z0-9\/\+].*/', '', $project->category ?? '') ?: 'Project';
@endphp

<div class="dp-split-section">
    <div class="container-fluid px-lg-5">
        <div class="row g-lg-5">
            
            {{-- ============================================ --}}
            {{-- LEFT COLUMN: Scrollable Content               --}}
            {{-- ============================================ --}}
            <div class="col-lg-7 dp-content-col order-2 order-lg-1">
                
                {{-- 1. Showcase Image --}}
                <div class="dp-showcase-wrap" data-dp-anim="slide-right">
                    <div class="dp-showcase-frame">
                        <img src="{{ asset($project->image) }}"
                             alt="{{ $cleanTitle }}"
                             class="dp-showcase-img"
                             loading="lazy">
                    </div>
                </div>

                {{-- 2. Narrative/Workflow Section --}}
                @if($project->flow_description)
                <div class="dp-narrative-wrap">
                    <div class="dp-narrative-body">
                        {{-- Timeline line draws on scroll --}}
                        <div class="dp-narrative-timeline" aria-hidden="true">
                            <div class="dp-narrative-timeline-track"></div>
                            <div class="dp-narrative-timeline-dot"></div>
                        </div>

                        <div class="dp-narrative-header" data-dp-anim="fade-up">
                            <span class="dp-eyebrow" data-i18n="project.flow">Alur Pengerjaan</span>
                        </div>

                        <div class="dp-narrative-text" data-dp-anim="fade-up" data-i18n="project.flow_description.{{ $project->slug }}">
                            {!! nl2br(e($project->flow_description)) !!}
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- ============================================ --}}
            {{-- RIGHT COLUMN: Sticky Sidebar                  --}}
            {{-- ============================================ --}}
            <div class="col-lg-5 dp-sidebar-col order-1 order-lg-2">
                <div class="dp-sidebar-sticky">
                    
                    {{-- Back to Portfolio --}}
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <a href="{{ url('/#resources') }}" class="dp-back-btn">
                            <i class="bi bi-arrow-left me-2"></i>
                            <span data-i18n="project.back">Kembali ke Portfolio</span>
                        </a>
                    </div>

                    {{-- Title --}}
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <h1 class="dp-project-title" data-i18n="project.title.{{ $project->slug }}">{!! $cleanTitle !!}</h1>
                    </div>

                    {{-- Description --}}
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <p class="dp-project-desc" data-i18n="project.description.{{ $project->slug }}">{{ $project->description }}</p>
                    </div>

                    {{-- Tech Stack (Plain Text) --}}
                    @if($project->tech_stack_badges)
                    <div class="dp-sidebar-element" data-dp-anim="fade-up">
                        <p class="dp-tech-plain-text">
                            {{ implode(', ', array_column($project->tech_stack_badges, 'name')) }}
                        </p>
                    </div>
                    @endif

                    {{-- CTA Action Buttons --}}
                    <div class="dp-sidebar-element dp-cta-wrap" data-dp-anim="fade-up">
                        @if($project->live_demo_url)
                            <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener" class="dp-btn-cta dp-btn-cta--primary">
                                <span data-i18n="project.live_demo">Live Demo</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        @endif

                        @if($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="dp-btn-cta dp-btn-cta--secondary">
                                <i class="bi bi-github"></i>
                                <span data-i18n="project.github">Lihat di GitHub</span>
                            </a>
                        @else
                            <div class="dp-btn-cta dp-btn-cta--locked" title="Repository ini bersifat privat untuk menjaga kerahasiaan data agensi/klien.">
                                <i class="bi bi-lock-fill"></i>
                                <span data-i18n="project.private_repo">Repositori Privat</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
    @vite(['resources/js/detail-animations.js'])
@endpush
