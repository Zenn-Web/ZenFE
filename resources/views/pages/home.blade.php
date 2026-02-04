@extends('layout.welcome')


@section('content')


<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">

    <a class="navbar-brand" href="#">Zenifen</a>

    <!-- TOGGLER (HP) -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
      <ul class="navbar-nav gap-3">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<section class="bg-white text-black py-5 min-vh-100 d-flex align-items-center">
    <div class="container"> 
        <div class="row align-items-center justify-content-center"> 
            
            <div class="col-lg-6 text-lg-start text-center mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold">Hello, I am Zenifen</h1>
                <p class="lead opacity-75 mb-4">Web Developer | Front End Engineer</p>
                <a href="#" class="btn btn-custom-portfolio btn-lg px-4">Lihat Portofolio</a>
            </div>

            <div class="col-lg-6 d-flex justify-content-center">
                <div class="profile-animation-wrapper">
                    
                    <div class="animated-gradient-bg"></div>
                    <div class="pulsing-circle-outer"></div>
                    <div class="pulsing-circle-inner"></div>
                    <div class="profile-picture-container">
                        <img src="{{ asset('img/foto_portofolio_animate.png') }}" class="img-fluid profile-image" alt="Zenifen">
                    </div>
                    <div class="ornamen-box computer">🖥️</div>
                    <div class="ornamen-box greek">🏛</div>
                   <div class="status-badge">
    <span class="dot"></span> 
    <span class="status-text">Ready to work</span>
    <span id="live-clock">00:00</span>
</div>
                </div> </div> </div> </div> </section>

<section class="about-section py-5 bg-white">
    <div class="container">
        <div class="card profile-card-horizontal border-dark shadow-lg overflow-hidden">
            <div class="row g-0 align-items-center">
                <div class="col-md-4 profile-img-container bg-white text-center py-5">
                    <div class="image-wrapper shadow">
                        <img src="{{ asset('img/foto-kamu.jpg') }}" alt="Profile" class="img-fluid rounded-circle border border-4 border-white">
                    </div>
                </div>
                <div class="col-md-8 bg-white">
                    <div class="card-body p-4 p-lg-5">
                        <h6 class="text-uppercase fw-bold text-muted mb-2 tracking-widest animate-text">About Me</h6>
        
                        <p class="text-secondary mb-4 animate-text">
                            Berpengalaman dalam membangun aplikasi web yang responsif dan performa tinggi. Fokus pada struktur kode yang bersih dan pengalaman pengguna yang intuitif.
                        </p>
                        
                        <div class="d-flex flex-wrap gap-3 mt-4 animate-buttons">
                            
                            <div class="d-flex align-items-center gap-2">
                                <a href="#" class="social-link border border-dark rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link border border-dark rounded-circle"><i class="fab fa-github"></i></a>
                                <a href="#" class="social-link border border-dark rounded-circle"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
               


<section class="py-5 bg-white text-dark d-flex" style="margin-top: -1px;"> 
    <div class="container-fluid px-lg-5"> <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Keahlian Saya</h2>
            <p class="text-muted">Beberapa teknologi yang sedang saya pelajari untuk membangun website.</p>
        </div>

        <div class="row g-5 justify-content-center">
            
            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/HTML5_logo_and_wordmark.svg.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">HTML</h4>
                        <p class="text-muted mb-0">Dasar utama struktur halaman website.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/CSS-Logo-2011.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">CSS</h4>
                        <p class="text-muted mb-0">Mengatur tampilan dan estetika website.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/PYTHON_LOGO-removebg-preview.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">Python</h4>
                        <p class="text-muted mb-0">Logika pemrograman dan pengolahan data.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card skill-card h-100 text-center border-0 shadow py-4">
                    <div class="card-body">
                        <img src="{{ asset('img/laravel_logo-removebg-preview.png') }}" class="img-fluid mb-4" style="height: 80px; object-fit: contain;">
                        <h4 class="fw-bold mb-2">Laravel</h4>
                        <p class="text-muted mb-0">Framework profesional untuk sistem backend.</p>
                    </div>
                </div>
            </div>

        </div> </div> </section>


 <section class="bg-white text-black py-5 max-vh-100 d-flex align-items-center">          
<div class="container">


    <h2 class="fw-bold mb-4">My Resources</h2>

   <div class="container">
    <div class="col-md-6 col-lg-4 d-flex justify-content-center">
    <div class="card resource-card h-100 w-100 border-0 shadow-lg p-4" style="border-radius: 20px; border: 1px solid #000000;">
        
   <div class="img-container d-flex align-items-center justify-content-center mb-3" 
     style="background-color: transparent; border-radius: 15px; height: 200px; overflow: hidden;">
     
    <img src="{{ asset('img/eyegil.porto.png') }}"
         class="img-fluid"
         style="max-height: 100%; width: auto; object-fit: contain; border: 1px solid #e0e0e0; border-radius: 8px;" 
         alt="UX Audit Checklist">
</div>
        <div class="card-body p-0 text-start"> <span class="badge bg-light text-primary mb-2 px-3 py-2">UX</span>
            <h5 class="fw-bold mb-2">UX Audit Checklist</h5>
            <p class="text-muted small mb-0">
                Checklist untuk mengevaluasi dan meningkatkan pengalaman pengguna.
            </p>
        </div>
    </div>
</div>
</div>

</section>

@endsection
