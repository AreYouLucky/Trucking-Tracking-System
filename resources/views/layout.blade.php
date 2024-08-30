<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
        @vite('resources/js/app.js')
        <title>Lara's Trucking Services</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

        <!-- Leaflet Routing Machine CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

        <!-- Vue.js -->
        <script src="https://unpkg.com/vue@3"></script>

        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <!-- Leaflet Routing Machine JS -->
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

        <script src="https://use.fontawesome.com/releases/v6.2.0/js/all.js"></script>

        <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
            ({key: "AIzaSyBd-kQ7xyVmRuUL3Dk-UiByLAny4HVE46I", v: "beta"});</script>


        <!-- Styles -->
    </head>
    <body>
        <div id="app">
           @yield('contents')
        </div>

    </body>
</html>
<style>
    body {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='560' preserveAspectRatio='none' viewBox='0 0 1440 560'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1059%26quot%3b)' fill='none'%3e%3crect width='1440' height='560' x='0' y='0' fill='rgba(18%2c 197%2c 255%2c 1)'%3e%3c/rect%3e%3cpath d='M0%2c517.499C112.209%2c514.808%2c237.448%2c576.977%2c329.878%2c513.3C422.419%2c449.546%2c393.208%2c302.234%2c451.724%2c206.295C522.291%2c90.598%2c721.07%2c33.205%2c704.76%2c-101.329C688.591%2c-234.699%2c490.55%2c-250.045%2c386.149%2c-334.6C304.734%2c-400.539%2c259.494%2c-511.93%2c159.436%2c-542.991C58.948%2c-574.185%2c-44.255%2c-523.493%2c-146.828%2c-500.051C-256.606%2c-474.963%2c-380.737%2c-478.171%2c-462.589%2c-400.835C-546.663%2c-321.4%2c-590.713%2c-199.965%2c-586.789%2c-84.367C-583.086%2c24.698%2c-482.81%2c100.651%2c-442.126%2c201.912C-398.626%2c310.18%2c-435.173%2c461.58%2c-340.204%2c529.368C-245.939%2c596.654%2c-115.783%2c520.275%2c0%2c517.499' fill='%230086b2'%3e%3c/path%3e%3cpath d='M1440 1057.441C1548.936 1067.507 1661.1109999999999 1103.713 1761.541 1060.328 1868.2 1014.252 1960.336 924.9259999999999 1999.754 815.631 2038.2069999999999 709.012 1993.013 594.741 1969.443 483.878 1947.431 380.344 1931.017 274.321 1866.783 190.19099999999997 1800.1770000000001 102.95400000000001 1712.041 18.341999999999985 1603.2 4.191000000000031 1497.161-9.595000000000027 1415.003 96.77100000000002 1310.165 117.82299999999998 1189.866 141.97899999999998 1050.891 65.613 949.947 135.36700000000002 848.046 205.78300000000002 818.239 345.48699999999997 802.847 468.391 787.63 589.904 796.624 720.751 863.816 823.134 927.749 920.552 1046.569 960.3399999999999 1154.508 1004.2339999999999 1246.034 1041.453 1341.615 1048.35 1440 1057.441' fill='%2372dcff'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1059'%3e%3crect width='1440' height='560' fill='white'%3e%3c/rect%3e%3c/mask%3e%3c/defs%3e%3c/svg%3e");
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        width: 100%;
        height: 100vh;
        position: absolute;

    }
    * {
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        font-variation-settings:
            "thin"0;
    }
</style>