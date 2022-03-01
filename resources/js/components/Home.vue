<template>
  <div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center pt-5">REGON REGISTRATION FORM</h2>
                    <hr>
                    <div class="row my-2">
                        <div class="offset-4 col-md-4">
                            <div>
                                <slot name="errors"/>
                            </div>

                            <form class="border p-4" action="/accounts" method="POST">
                                <slot name="csrf"/>
                                <div class="mb-3">
                                    <label for="tax_id" class="form-label">Tax ID</label>
                                    <input type="password" name="tax_id" class="form-control" id="tax_id" placeholder="Enter your tax id">
                                    </div>
                                <button type="submit" class="btn btn-primary">Add opinions</button>
                                <button type="submit" class="btn btn-primary float-right">Check company</button>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="offset-4 col-md-4 alert alert-info">
                            <slot name="message"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Home",
  props: {

  },
  data() {
    return {};
  },
  created() {

  },
  methods: {},
};
</script>

<style lang="scss" scoped></style>
