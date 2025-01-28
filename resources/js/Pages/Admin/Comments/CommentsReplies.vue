<template>
    <AdminLayout>
        <ProductList :comments = "comments" :replies="replies">
            
        </ProductList>

    </AdminLayout>

</template>

<script setup>
defineProps({
    comments:Array,
    replies:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import ProductList from './CommentsList.vue';
</script>