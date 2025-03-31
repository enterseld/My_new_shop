<template>
    <AdminLayout>
        <Catalog :products = "products">
            
        </Catalog>

    </AdminLayout>

</template>

<script setup>
defineProps({
    products:Array
})
import Catalog from '@/Pages/User/Catalog.vue';
import AdminLayout from '../Components/AdminLayout.vue';

</script>