<template>
    <form :action="action" method="post">
        <slot></slot>
        <button type="submit" class="btn btn-primary">{{ btn }}</button>
    </form>
</template>

<script>
    export default {
        props: ['action', 'btn']
    }
</script>