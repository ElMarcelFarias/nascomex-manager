<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-layout-item md-medium-size-100 md-size-100">
        <edit-profile-form data-background-color="green"> </edit-profile-form>
        <add-import-form data-background-color="green"></add-import-form>
      </div>
      <div class="md-layout-item md-medium-size-100 md-size-33">
      </div>
    </div>
  </div>
</template>

<script>
import { AddImportForm } from "@/pages";

export default {
  components: {
    AddImportForm
  },
};
</script>
