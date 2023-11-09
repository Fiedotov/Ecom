<script setup>
import InputError from '@/Components/InputError.vue';
import CtaButton from '@/Components/CtaButton.vue';
import TextInput from '@/Components/Input.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import PasswordField from '@/Components/PasswordInput.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.post(route('passwords.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Update Password</h2>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <div>
        <label for="">Current Password</label>

        <PasswordField
            id="current_password2"
            ref="currentPasswordInput"
            v-model="form.current_password"
            class="mt-1 block w-full"
            autocomplete="current-password"
        />

        <InputError :message="form.errors.current_password" class="mt-2" />
      </div>

      <div>
        <label for="">New Password</label>

        <PasswordField
            id="password"
            ref="passwordInput"
            v-model="form.password"
            class="mt-1 block w-full"
            autocomplete="new-password"
        />

        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <div>
        <label for="">Confirm Password</label>

        <PasswordField
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="mt-1 block w-full"
            autocomplete="new-password"
        />

        <InputError :message="form.errors.password_confirmation" class="mt-2" />
      </div>

      <div class="flex items-center gap-4">
        <cta-button :disabled="form.processing">Save</cta-button>

        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
          <p v-if="form.recentlySuccessful" class="text-sm text-green-500 flex items-center">
            <i class="fa fa-check mr-2"></i> Password Updated
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>