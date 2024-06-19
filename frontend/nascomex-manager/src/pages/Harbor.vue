<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <add-harbor-form data-background-color="green"></add-harbor-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-33">
        <user-card> </user-card>
      </div>
    </div>
  </div>
</template>

<script>
import { AddHarborForm } from "@/pages";

export default {
  components: {
    AddHarborForm
  },
};
</script>
