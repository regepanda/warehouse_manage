<!DOCTYPE html>
<head>
    @section("head")
        <script src="/lib/jquery/jquery-1.9.1.min.js"></script>
        <script src="/lib/myClass/polling.js"></script>
        @include("lib.bs_ui")
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>

        <link rel="stylesheet" href="/style/base.css"/>
    @show

</head>
<body ng-app="warehouse">
    @section("body")

    @show
    @include("lib.component")
</body>
@section("bottom")

@show
</html>
