<nav class="layout-footer footer bg-footer-theme">
    <div class="container-fluid d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center container-p-x pb-3">
      <div class="pt-3">
        Copyright © {{ now()->format('Y') }} <span class="footer-text font-weight-bolder"> {{ str_replace('<br>', ' ', $config['website_name']) }}<span>
      </div>
      <div class="pt-3">
        <span class="footer-text d-flex"><a href="https://www.4visionmedia.com" target="_blank" title="Perusahaan Jasa Pembuatan Website, Aplikasi, Digital Marketing, Design Company Profile, Software" style="display: inline-block; width: 100px; margin-left: 10px;"><img src="{{ asset('assets/tmplts_backend/images/logo-4vm.svg') }}"></a></span>
      </div>
      <!-- <div>
        {{-- <a href="javascript:void(0)" class="footer-link pt-3">About Us</a>
        <a href="javascript:void(0)" class="footer-link pt-3 ml-4">Help</a>
        <a href="javascript:void(0)" class="footer-link pt-3 ml-4">Contact</a>
        <a href="javascript:void(0)" class="footer-link pt-3 ml-4">Terms &amp; Conditions</a> --}}
      </div> -->
    </div>
</nav>
