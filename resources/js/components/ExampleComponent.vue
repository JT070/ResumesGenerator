<template>
    <div class="card">
      <div class="card-header">Skills:</div>
      <div class="card-body">
        <!-- "v-bind:key" es lo mismo que ":key" -->
        <div v-for="(input, i) in inputs" :key="i" class="d-flex mb-2 p-0">
          <div class="col-10">
            <!-- v-model: Es para mantener sincronizados los valores del elemento con una variable dada -->
            <input type="text" class="form-control" name="skills[]" v-model="inputs[i]" />
          </div>
          <div class="col-2">
            <button class="btn btn-danger" @click.prevent="remove(i)">Delete</button>
          </div>
        </div>
        <div>
            <button class="ml-3 btn btn-primary" @click.prevent="add()">
              Add
            </button>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  data() {
    return {
      inputs: ["uno", "dos", "tres"],
    };
  },
  methods: {
    add() {
      this.inputs.push('');
    },
    remove (i) {
        this.inputs.splice(i, 1);
    }
  },
};
</script>
