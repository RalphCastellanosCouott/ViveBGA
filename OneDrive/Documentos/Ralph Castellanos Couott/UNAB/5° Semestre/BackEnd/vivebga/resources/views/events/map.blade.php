@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1100px; margin: 20px auto;">
    <h1>Mapa de eventos</h1>
    <p>Se muestran eventos actuales y próximos. Haz click en un marcador para ver info y acceder al evento.</p>

    {{-- Mapa --}}
    <div id="map" style="width: 100%; height: 500px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
</div>

{{-- Pasamos los datos de eventos a JS --}}
<script>
    const eventos = @json($eventosMap);
</script>

<script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 7.1254, lng: -73.1198 }, // Bucaramanga
            zoom: 13,
            styles: [
                {
                    featureType: "poi.business",
                    stylers: [{ visibility: "off" }]
                },
                {
                    featureType: "transit",
                    elementType: "labels.icon",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });

        const eventos = @json($eventosMap);

        eventos.forEach(evento => {
            if (!evento.lat || !evento.lng) return;

            const marker = new google.maps.Marker({
                position: { lat: evento.lat, lng: evento.lng },
                map,
                title: evento.nombre,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png", // puedes cambiar color
                    scaledSize: new google.maps.Size(40, 40)
                }
            });

            const content = `
                <div style="max-width: 250px;">
                    <h3 style="margin-bottom: 5px;">${evento.nombre}</h3>
                    ${evento.imagen ? `<img src="${evento.imagen}" style="width: 100%; border-radius: 8px;">` : ''}
                    <p><strong>Precio:</strong> ${evento.precio ?? 'Gratis'}</p>
                    <p><strong>Dirección:</strong> ${evento.direccion}</p>
                    <a href="${evento.url}" style="
                        display: inline-block;
                        background-color: #2563eb;
                        color: white;
                        padding: 6px 12px;
                        border-radius: 6px;
                        text-decoration: none;
                        font-weight: bold;
                    ">Ver evento</a>
                </div>
            `;

            const infoWindow = new google.maps.InfoWindow({ content });

            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });
        });
    }
</script>


{{-- Cargar la API de Google Maps --}}
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps') }}&callback=initMap">
</script>
@endsection
