  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      @if (!auth()->user())
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              <script>
                document.write(new Date().getFullYear())
              </script> Développé par
              <a style="color: #252f40;" href="#" class="font-weight-bold ml-1" target="_blank">NEW BRAIN FACTORY</a>
            </p>
          </div>
        </div>
      @endif
    </div>
  </footer>
