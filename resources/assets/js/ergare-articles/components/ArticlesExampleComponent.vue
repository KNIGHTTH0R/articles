<template>
    <div>
        Articles example: {{data}}
    </div>
</template>

<style>

</style>

<script>
  export default {
    name: 'articles-example',
    data() {
      return {
        data: 'example'
      }
    },
    mounted() {
      console.log('Mounted ok')
    }
  }
</script>
