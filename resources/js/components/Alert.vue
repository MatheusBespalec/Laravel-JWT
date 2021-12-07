<template>
    <div :class="alert" role="alert">
        <h4 class="alert-heading">{{ title }}</h4>
        <hr>
        <p class="mb-0"><slot></slot></p>
    </div>
</template>

<script>
    export default {
        props: ['title', 'type'],
        computed: {
            alert() {
                return 'alert alert-' + this.type
            }
        }
    }
</script>