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


<section class="bg-black text-white min-vh-100 d-flex align-items-center">
    <div class="container"> 
        <div class="row align-items-center"> 
            <div class="col-lg-6 d-flex justify-content-center mb-4">
                <div class="profile-wrapper">
                    <div class="position-absolute rounded-circle bg-success opacity-25"
                         style="width: 420px; height: 420px; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0;">
                    </div>
                    <img src="{{ asset('img/foto_portofolio.png') }}" alt="Zenifen" class="img-main position-relative">
                    <div class="ornamen-box rocket">🚀</div>
                    <div class="ornamen-box glasses">😎</div>
                    <div class="status-badge"><span class="dot"></span> Ready to work</div>
                </div>
            </div>

            <div class="col-lg-6 text-lg-start text-center">
                <h1 class="display-4 fw-bold">Hello, I am Zenifen</h1>
                <p class="lead opacity-75 mb-4">Web Developer | Front End Engineer</p>
                <a href="#" class="btn btn-outline-light btn-lg px-4">Lihat Portofolio</a>
            </div>
        </div> 
    </div> 
</section>

<section class="py-5 bg-white text-dark"> 
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Tentang Saya</h2>
        <p class="text-center"> 
            Saya adalah seorang pengembang web yang masih mau terus belajar dan mengembangkan kemampuan di bidang pemrograman, khususnya HTML, PHP, Python, dan Laravel. Saya mau belajar membangun website dan aplikasi web yang terstruktur, dinamis, serta responsif dengan memanfaatkan Laravel sebagai framework utama dan HTML untuk menyusun tampilan antarmuka. 
            Pada sisi backend, saya mau belajar menggunakan PHP untuk mengelola logika aplikasi dan integrasi data, serta Python untuk pengolahan data dan otomatisasi sederhana. Saya memiliki semangat belajar yang tinggi, terbuka terhadap hal baru, dan berkomitmen untuk terus meningkatkan kemampuan agar dapat menghasilkan solusi digital yang lebih baik.
        </p>
    </div>
</section>

<section class="py-5 bg-white text-dark" style="margin-top: -1px;"> 
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
                
            </div> 
        </div> </div> </section>


