<template>
    <main>
      <l-map ref="map" v-model:zoom="zoom" v-model:center="center" :useGlobalLeaflet="false">
        <l-tile-layer url="https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png"
                      layer-type="base"
                      name="Stadia Maps Basemap"></l-tile-layer>
      </l-map>
    </main>
  </template>
  <script setup>
import { SearchControl, OpenStreetMapProvider } from 'leaflet-geosearch';

const searchControl = new SearchControl({
  notFoundMessage: 'Sorry, that address could not be found.',
  provider: new OpenStreetMapProvider(),
  style: 'bar',
});

map.addControl(searchControl);
  </script>