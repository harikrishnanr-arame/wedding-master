@extends('layouts.app')

@section('title', 'Wedding Templates')

@section('content')
    <!--
        Welcome page template for the wedding website.
        This view displays the main landing page with hero section, available wedding templates,
        customer testimonials, and a call-to-action section.
    -->
    <section class="hero" style="background-image: url({{ asset('assets/img/bg.png') }});">
      <!-- LEFT TEMPLATE STACK -->
      <div class="template-stack left">
        <img class="img-one" src="{{ asset('assets/img/i4.png') }}" alt="Template 1">
        <img class="img-two" src="{{ asset('assets/img/i2.png') }}" alt="Template 2">
        <img class="img-three" src="{{ asset('assets/img/i3.png') }}" alt="Template 3">
      </div>
      <!-- RIGHT TEMPLATE STACK -->
      <div class="template-stack right">
        <img class="img-one" src="{{ asset('assets/img/i1.jpg') }}" alt="Template 4">
        <img class="img-two" src="{{ asset('assets/img/i5.png') }}" alt="Template 5">
        <img class="img-three" src="{{ asset('assets/img/i.jpg') }}" alt="Template 6">
      </div>
      <!-- HERO CONTENT -->
      <div class="hero-content">
        <h1>BEST WEDDING TEMPLATES</h1>
        <p>BEST WEDDING TEMPLATES</p>
        <button class="hero-btn">Purchase now</button>
        <div class="scroll-indicator">
          <span></span>
        </div>
      </div>
    </section>
    <!-- Template showing -->
    <section class="templates-section">
      <div class="container">
        <!-- TOP DECOR -->
        <div class="section-decor" id="templates">
          <img src="{{ asset('assets/img/floraldesign.png') }}" alt="floral">
        </div>
        <!-- HEADING -->
        <h2 class="section-title">Wedding and Invitation Templates</h2>
        <p class="section-subtitle">
          We Happily Prepared 09 Homes for Your Big Day. Pick One of Our Beautiful Homepages
          to Start Your Dreamy Wedding Website!
        </p>
        <!-- TEMPLATES GRID -->
        <div class="templates-grid">
          <!-- TEMPLATE CARD -->
          <div class="template-card">
            <img src="{{ asset('assets/img/i.jpg') }}" alt="Wedding Home 1">
            <h3>Wedding Home 1</h3>
            <div class="template-actions">
              <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
              <span class="heart">♡</span>
            </div>
          </div>
          <div class="template-card">
            <img src="{{ asset('assets/img/i1.jpg') }}" alt="Muslim Wedding Home">
            <h3>Muslim Wedding Home</h3>
            <div class="template-actions">
              <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
              <span class="heart">♡</span>
            </div>
          </div>
          <div class="template-card">
            <img src="{{ asset('assets/img/i2.png') }}" alt="Announcement Home 2">
            <h3>Announcement Home 2</h3>
            <div class="template-actions">
              <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
              <span class="heart">♡</span>
            </div>
          </div>
          <div class="template-card">
            <img src="{{ asset('img/i3.png') }}" alt="Wedding Home 1">
            <h3>Wedding Home 1</h3>
            <div class="template-actions">
              <button onclick="location.href='{{ url('/template-edit') }}'">Choose This Template</button>
              <span class="heart">♡</span>
            </div>
          </div>
          <div class="template-card">
            <img src="{{ asset('img/i4.png') }}" alt="Muslim Wedding Home">
            <h3>Muslim Wedding Home</h3>
            <div class="template-actions">
              <button onclick="location.href='{{ url('/template-edit') }}'">Choose This Template</button>
              <span class="heart">♡</span>
            </div>
          </div>
          <div class="template-card">
            <img src="{{ asset('img/i5.png') }}" alt="Announcement Home 2">
            <h3>Announcement Home 2</h3>
            <div class="template-actions">
              <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
              <span class="heart">♡</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- review -->
    <section class="testimonials-section" style="background-image: url({{ asset('assets/img/bg2.png') }});">
      <div class="container">
        <h2 class="testimonials-title">CUSTOMER LOVE & REAL MOMENTS</h2>
        <div class="testimonials-grid">
          <!-- ITEM 1 -->
          <div class="testimonial-item">
            <img src="{{ asset('assets/img/reviewimg/review1.jpg') }}" alt="Couple">
            <div class="testimonial-text">
              <p>
                “We always enjoyed this is rare time
                has this your the we’t need.”
              </p>
              <span>JANET NAJON</span>
            </div>
          </div>
          <!-- ITEM 2 -->
          <div class="testimonial-item reverse">
            <div class="testimonial-text">
              <p>
                “We wit cut the cros is your
                ang great time fike.”
              </p>
              <span>NIRMAY NAITOM</span>
            </div>
            <img src="{{ asset('assets/img/reviewimg/review2.jpg') }}" alt="Couple">
          </div>
          <!-- ITEM 3 -->
          <div class="testimonial-item">
            <img src="{{ asset('img/reviewimg/review3.webp') }}" alt="Couple">
            <div class="testimonial-text">
              <p>
                “This is tier your eest for you once
                thur and we ean lat your line e of
                this mes.”
              </p>
              <span>JANKAMY DADON</span>
            </div>
          </div>
          <!-- ITEM 4 -->
          <div class="testimonial-item reverse">
            <div class="testimonial-text">
              <p>
                “We is your ner you ever yolle
                ang os soess that raynox.”
              </p>
              <span>JANKAM MAIDON</span>
            </div>
            <img src="{{ asset('img/reviewimg/review4.jpg') }}" alt="Couple">
          </div>
        </div>
      </div>
    </section>
    <!-- footer  -->
    <section class="cta-section" style="background-image: url({{ asset('assets/img/bg2.png') }});">
      <div class="cta-content">
        <h2>
          Let`s Build Your Wedding<br>
          Website With Us.
        </h2>
        <a href="#templates" class="cta-btn">
        Browse Templates
        </a>
        <div class="cta-copyright">
          © 2026 AraMe Global. All rights reserved.
        </div>
      </div>
    </section>
@endsection