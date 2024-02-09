<script setup>
import Hero from './Hero.vue';
import Header from './Header.vue';

import Footer from './Footer.vue';
</script>

<template>
    <Header> </Header>


    <Hero/>

    <slot></slot>

    <Footer> </Footer>


</template>