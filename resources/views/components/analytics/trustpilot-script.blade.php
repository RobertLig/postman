<div>
    @if (request()->routeIs('home') && consent('analytics'))
        <script type="text/javascript" src="https://widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async>
        </script>
    @endif
</div>
