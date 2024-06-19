<template>
  <div class="form-container">
    <div class="content">
      <div class="md-layout">
        <div class="md-layout-item md-medium-size-100 md-size-100">
          <add-shipping-form data-background-color="green"></add-shipping-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { AddShippingForm } from "@/pages";

export default {
  components: {
    AddShippingForm
  },
};
</script>

