<template>
    <div class="form-group">
        <label>{{ label }}</label>
        <input class="form-control" :type="type" :name="name" :placeholder="placeholder">
    </div><!-- form-group -->
</template>

<script>
    export default {
        props: ['name', 'label', 'placeholder', 'type']
    }
</script>