
<script setup lang="ts">
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

interface Organization {
    id: number;
    name: string;
    role: string;
}

interface OrganizationPageProps {
    activeOrganization: Organization | null;
    availableOrganizations: Organization[];
}

const page = usePage();

const organizations = computed(() => {
    const props = page.props as unknown as OrganizationPageProps;

    return props.availableOrganizations ?? [];
});

const activeOrganization = computed(() => {
    const props = page.props as unknown as OrganizationPageProps;

    return props.activeOrganization;
});

function changeOrganization(event: Event) {
    const select = event.target as HTMLSelectElement;
    const organizationId = Number(select.value);

    if (!organizationId) {
        return;
    }

    router.post('/organizations/active', {
        organization_id: organizationId,
    }, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div class="space-y-2 px-2 py-3">
        <label
            for="organization-switcher"
            class="text-xs font-semibold text-muted-foreground"
        >
            ORGANIZACIÓN ACTIVA
        </label>

        <select
            id="organization-switcher"
            :value="activeOrganization?.id ?? ''"
            @change="changeOrganization"
            class="w-full rounded-lg border
                   border-border bg-background
                   px-3 py-2 text-sm text-foreground"
        >
            <option value="" disabled>
                Seleccionar empresa
            </option>

            <option
                v-for="organization in organizations"
                :key="organization.id"
                :value="organization.id"
            >
                {{ organization.name }}
            </option>
        </select>

        <p
            v-if="activeOrganization"
            class="text-xs text-muted-foreground"
        >
            Rol: {{ activeOrganization.role }}
        </p>
    </div>
</template>
