<nav class="navbar navbar-expand-lg navbar-light custom-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand brand-logo" href="/#home">
            Zenifen<span class="dot">.</span>
        </a>

        <!-- HAMBURGER CUSTOM (Animated) -->
        <button class="navbar-toggler custom-toggler" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto nav-links-group">

                <li class="nav-item">
                    <a class="nav-link" href="/#home">
                        <i class="bi bi-house d-lg-none"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_home', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_home', [], 'en') }}">{{ __('portfolio.nav_home') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#about">
                        <i class="bi bi-person d-lg-none"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_about', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_about', [], 'en') }}">{{ __('portfolio.nav_about') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#skills">
                        <i class="bi bi-journal-code d-lg-none"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_skills', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_skills', [], 'en') }}">{{ __('portfolio.nav_skills') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/#resources">
                        <i class="bi bi-grid d-lg-none"></i>
                        <span data-i18n-id="{{ __('portfolio.nav_projects', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_projects', [], 'en') }}">{{ __('portfolio.nav_projects') }}</span>
                    </a>
                </li>
            </ul>

            <div class="nav-actions d-flex align-items-center gap-3 gap-lg-4">
                <div class="utility-toggles d-flex gap-2">
                    <!-- Language Toggle Button -->
                    <button id="lang-toggle" class="btn-lang-toggle" aria-label="Switch Language">
                        <span class="lang-label">{{ app()->getLocale() === 'en' ? 'ID' : 'EN' }}</span>
                    </button>

                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle Theme">
                        <i class="bi bi-sun-fill sun-icon"></i>
                        <i class="bi bi-moon-fill moon-icon"></i>
                    </button>
                </div>

                <a href="/#contact" class="btn-contact-me">
                    <i class="bi bi-chat-dots me-2 d-lg-none"></i>
                    <span data-i18n-id="{{ __('portfolio.nav_contact', [], 'id') }}" data-i18n-en="{{ __('portfolio.nav_contact', [], 'en') }}">{{ __('portfolio.nav_contact') }}</span>
                </a>
            </div>
        </div>
    </div>
</nav>