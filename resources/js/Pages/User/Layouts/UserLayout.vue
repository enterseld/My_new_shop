<script setup>
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from './Header.vue';
import Footer from './Footer.vue';
import Hero from './Hero.vue';
onMounted(() => {
    initFlowbite();
})

</script>

<template>
    <!--Header-->
    <Header></Header>
    <!--End-->

    <!--Main content-->
    
    <slot />

    <!--End-->

    <!--Footer-->
    <Footer></Footer>
    <!--End-->
</template>