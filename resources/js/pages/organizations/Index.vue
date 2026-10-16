
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Organization {
    id: number;
    name: string;
    slug: string;
    status: string;
    role: string;
}

defineProps<{
    organizations: Organization[];
}>();

const form = useForm({
    name: '',
});

function createOrganization() {
    form.post('/organizations', {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Organizaciones" />

    <AppLayout>
        <div class="mx-auto max-w-5xl space-y-8 p-6">
            <div>
                <h1 class="text-3xl font-bold">
                    Organizaciones
                </h1>

                <p class="text-muted-foreground">
                    Administra las empresas de StackPilot.
                </p>
            </div>

            <form
                class="flex flex-col gap-3 rounded-xl border p-5"
                @submit.prevent="createOrganization"
            >
                <label for="name" class="font-medium">
                    Nueva organización
                </label>

                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    maxlength="150"
                    required
                    class="rounded-lg border p-3"
                    placeholder="Nombre de la empresa"
                />

                <p
                    v-if="form.errors.name"
                    class="text-sm text-red-500"
                >
                    {{ form.errors.name }}
                </p>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-blue-600 px-5 py-3 text-white disabled:opacity-50"
                >
                    Crear organización
                </button>
            </form>

            <div class="space-y-4">
                <h2 class="text-xl font-semibold">
                    Mis organizaciones
                </h2>

                <p
                    v-if="organizations.length === 0"
                    class="text-muted-foreground"
                >
                    Todavía no perteneces a ninguna organización.
                </p>

                <div
                    v-for="organization in organizations"
                    :key="organization.id"
                    class="rounded-xl border p-5"
                >
                    <h3 class="text-lg font-semibold">
                        {{ organization.name }}
                    </h3>

                    <p class="text-sm text-muted-foreground">
                        {{ organization.slug }}
                    </p>

                    <p class="mt-2 text-sm">
                        Rol: {{ organization.role }}
                    </p>

                    <p class="text-sm">
                        Estado: {{ organization.status }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
