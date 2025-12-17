import './bootstrap';
import "flowbite";

import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

window.THREE = THREE;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    AOS.init({
      duration: 900,
      offset: 0,
      once: false,
      mirror: false,
    });
    AOS.refresh();
  }, 100);
});
window.addEventListener('load', () => AOS.refresh());

