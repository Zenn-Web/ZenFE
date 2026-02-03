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

                    <div class="audio-visualizer">
                        <span class="bar bar-1"></span><span class="bar bar-2"></span><span class="bar bar-3"></span>
                        <span class="bar bar-4"></span><span class="bar bar-5"></span><span class="bar bar-6"></span>
                        <span class="bar bar-7"></span><span class="bar bar-8"></span><span class="bar bar-9"></span>
                        <span class="bar bar-10"></span><span class="bar bar-11"></span><span class="bar bar-12"></span>
                        <span class="bar bar-13"></span><span class="bar bar-14"></span><span class="bar bar-15"></span>
                        <span class="bar bar-16"></span><span class="bar bar-17"></span><span class="bar bar-18"></span>
                        <span class="bar bar-19"></span><span class="bar bar-20"></span>
                    </div>

                    <div class="profile-picture-container">
                        <img src="{{ asset('img/foto_portofolio.png') }}" class="img-fluid profile-image" alt="Zenifen">
                    </div>

                    <div class="ornamen-box rocket">🚀</div>
                    <div class="ornamen-box glasses">😎</div>
                    <div class="status-badge"><span class="dot"></span> Ready to work</div>

                </div> </div> </div> </div> </section>
               


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

<section class="bg-white text-black py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-auto d-none d-md-flex flex-column align-items-center" style="width: 50px;">
                <span class="bg-black rounded-circle mb-2" style="width:12px; height:12px; flex-shrink: 0;"></span>
                <div class="bg-black opacity-50" style="width:2px; height: 100%; min-height: 400px;"></div>
            </div>

            <div class="col-md-10 col-lg-8"> 
                <div class="row mb-5">
                    <div class="col-md-3">
                        <p class="text-uppercase small opacity-75 mt-2 fw-bold">Profile</p>
                    </div>
                    <div class="col-md-9">
                         <p class="lh-lg opacity-90">
                            Saya berasal dari Jakarta dan pernah tinggal di Surabaya, pengalaman yang membantu saya beradaptasi dengan lingkungan dan budaya yang berbeda.
                            Saya memiliki ketertarikan untuk terus belajar dan berkembang, serta terbiasa menghadapi tantangan baru dengan sikap terbuka dan bertanggung jawab. Pengalaman berpindah
                            tempat tinggal dan menjalani kehidupan perkuliahan membentuk saya menjadi pribadi yang lebih mandiri, fleksibel, dan siap menghadapi berbagai situasi.
                         </p> </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="text-uppercase small opacity-75 mt-1 fw-bold">Intro</p>
                    </div>
                    <div class="col-md-9">
                        <div class="pe-md-3">
                            <p class="lh-lg opacity-90">
                                Saya saat ini berkuliah di Universitas Islam Indonesia, dengan fokus mengembangkan diri baik secara akademik maupun pribadi. Sebelumnya, saya mempelajari Stoicisme, yang membentuk pola pikir saya dalam kedisiplinan, ketahanan diri, dan refleksi diri. 
                                Di samping perkuliahan, saya memiliki ketertarikan yang besar terhadap teknologi dan pemrograman,
                                serta terus mempelajari bagaimana teknologi dapat digunakan untuk menciptakan solusi yang bermakna dan bermanfaat di masa depan.
                            </p>
                        </div>
                    </div>
                </div>
                
            </div> </section>

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
