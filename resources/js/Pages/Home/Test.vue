<script setup>
import { ref, watch } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import PropertyMap from "@/Components/PropertyMap.vue";
import Button from "primevue/button";
import Stepper from "primevue/stepper";
import StepList from "primevue/steplist";
import Step from "primevue/step";
import StepPanels from "primevue/steppanels";
import StepPanel from "primevue/steppanel";

const activeStep = ref(1);
const mapRef = ref(null);

watch(activeStep, (newStep) => {
    if (newStep === 2 && mapRef.value) {
        // Call the resizeMap method when step 2 becomes active
        mapRef.value.resizeMap();
    }
});
</script>

<template>
    <div class="container mx-auto px-4 py-6" dir="rtl">
        <div class="mb-10">
            <Stepper
                v-model:value="activeStep"
                class="w-full sophisticated-stepper"
            >
                <StepList
                    class="bg-slate-200 p-4 rounded-md min-h-[6rem] overflow-hidden"
                >
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="1"
                    >
                        <p>step one</p>
                    </Step>
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="2"
                    >
                        <p>step two</p>
                    </Step>
                </StepList>

                <StepPanels>
                    <StepPanel
                        v-slot="{ activateCallback }"
                        :value="1"
                        class=""
                    >
                        <p>step 1</p>
                        <div class="flex justify-end pt-4">
                            <Button
                                label="التالي"
                                icon="pi pi-arrow-left"
                                @click="activateCallback(2)"
                            />
                        </div>
                    </StepPanel>
                    <StepPanel v-slot="{ activateCallback }" :value="2">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <PropertyMap ref="mapRef" />
                        </div>
                        <div class="flex justify-between pt-4">
                            <Button
                                label="السابق"
                                icon="pi pi-arrow-right"
                                @click="activateCallback(1)"
                                icon-pos="right"
                            />
                        </div>
                    </StepPanel>
                </StepPanels>
            </Stepper>
        </div>
    </div>
</template>

<style scoped>
.sophisticated-stepper {
    width: 100%;
}
</style>
