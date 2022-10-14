<template>
    <Head title="Edit Skill" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Skill
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto bg-white sm:px-6 lg:px-8">
                 <div class="flex justify-end p-2 m-2">
                     <Link :href="route('skills.index')" class="px-4 py-2 text-white bg-indigo-500 rounded-md hover:bg-indigo-700">Back</Link>
                 </div>
                <form class="p-4" @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" type="text" class="block w-full mt-1" v-model="form.name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="image" value="Image" />
                        <TextInput id="image" type="file" class="block w-full mt-1" @input="form.image = $event.target.files[0]" />
                        <InputError class="mt-2" :message="form.errors.image" />
                    </div>


                    <div class="flex items-center justify-end mt-4">

                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Update
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import {Inertia} from '@inertiajs/inertia'
const props = defineProps({
    skill:Object
});

const form = useForm({
    name: props.skill?.name,
    image: null,
});

const submit = () => {
    Inertia.post(`/skills/${props.skill?.id}`,{
        _method:'put',
        name: form.name,
        image: form.image,
    })
};
</script>
