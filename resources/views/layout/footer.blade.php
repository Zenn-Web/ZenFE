<footer class="footer-section">
    <div class="container-fluid px-4 px-md-5">
        
        <!-- Center Stack: Brand Logo -> Inline Navigation Menu -> Social Icons -->
        <div class="d-flex flex-column align-items-center justify-content-center text-center">
            
            <!-- 1. Brand Logo -->
            <a class="footer-brand mb-3 d-inline-block" href="/#home">
                Zenifen<span class="dot">.</span>
            </a>

            <!-- 2. Centered Horizontal Navigation Menu -->
            <ul class="footer-nav-inline list-unstyled d-flex flex-wrap justify-content-center gap-3 gap-md-4 mb-3">
                <li><a href="/#home" data-i18n-id="{{ __('portfolio.footer_home', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_home', [], 'en') }}">{{ __('portfolio.footer_home') }}</a></li>
                <li><a href="/#about" data-i18n-id="{{ __('portfolio.footer_about', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_about', [], 'en') }}">{{ __('portfolio.footer_about') }}</a></li>
                <li><a href="/#skills" data-i18n-id="{{ __('portfolio.footer_skills', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_skills', [], 'en') }}">{{ __('portfolio.footer_skills') }}</a></li>
                <li><a href="/#resources" data-i18n-id="{{ __('portfolio.footer_projects', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_projects', [], 'en') }}">{{ __('portfolio.footer_projects') }}</a></li>
                <li><a href="/#contact" data-i18n-id="{{ __('portfolio.footer_contact', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_contact', [], 'en') }}">{{ __('portfolio.footer_contact') }}</a></li>
            </ul>

            <!-- 3. Social Media Icons -->
            <div class="footer-socials d-flex gap-3 justify-content-center mb-4">
                <a href="https://www.github.com/Zenn-Web" target="_blank" rel="noopener noreferrer" class="social-link-rounded github" aria-label="GitHub">
                    <i class="bi bi-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/zen-agusti-2928ba38a" target="_blank" rel="noopener noreferrer" class="social-link-rounded linkedin" aria-label="LinkedIn">
                    <i class="bi bi-linkedin"></i>
                </a>
                <a href="mailto:zenifenagusti70@gmail.com" class="social-link-rounded email" aria-label="Email">
                    <i class="bi bi-envelope"></i>
                </a>
                <a href="https://wa.me/6285174344683" target="_blank" rel="noopener noreferrer" class="social-link-rounded whatsapp" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>

        </div>

        <hr class="footer-divider mb-3">

        <!-- Copyright Row (Full width corner-to-corner) -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <p class="footer-copyright mb-0">
                &copy; {{ date('Y') }} Zenifen. <span data-i18n-id="{{ __('portfolio.footer_rights', [], 'id') }}" data-i18n-en="{{ __('portfolio.footer_rights', [], 'en') }}">{{ __('portfolio.footer_rights') }}</span>
            </p>
            <p class="footer-handcrafted mb-0">
                Made by Zen
            </p>
        </div>
    </div>
</footer>