<template>
    <AdminLayout>
        <CommentsList :comments = "comments" :replies="replies">
            
        </CommentsList>

    </AdminLayout>

</template>

<script setup>
defineProps({
    comments:Array,
    replies:Array
})
import AdminLayout from '../Components/AdminLayout.vue';

import CommentsList from './CommentsList.vue';
</script>