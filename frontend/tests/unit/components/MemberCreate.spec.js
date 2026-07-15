import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import MemberCreate from '@/modules/members/views/MemberCreate.vue';
import MemberService from '@/modules/members/services/MemberService';

const RouterLink = { template: '<a><slot></slot></a>' };

const mockRouter = {
    push: vi.fn()
};

vi.mock('vue-router', () => ({
    useRouter: () => mockRouter
}));

vi.mock('@/modules/members/services/MemberService', () => ({
    default: {
        createMember: vi.fn()
    }
}));

describe('MemberCreate.vue', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        // stub global alert
        global.alert = vi.fn();
    });

    it('renders the form correctly', () => {
        const wrapper = mount(MemberCreate, {
            global: { stubs: { RouterLink } }
        });
        expect(wrapper.text()).toContain('Register New Member');
        expect(wrapper.find('form').exists()).toBe(true);
    });

    it('submits form payload and redirects on success', async () => {
        MemberService.createMember.mockResolvedValueOnce({ data: { id: 1 } });

        const wrapper = mount(MemberCreate, {
            global: { stubs: { RouterLink } }
        });

        // Fill out form
        await wrapper.find('input[type="text"]').setValue('John');
        const inputs = wrapper.findAll('input[type="text"]');
        await inputs[1].setValue('Doe'); // last name
        await inputs[2].setValue(''); // middle name
        
        // Find all selects and set values
        const selects = wrapper.findAll('select');
        await selects[0].setValue('male'); // gender
        await selects[1].setValue('single'); // marital status
        await selects[2].setValue('employed'); // employment status

        await wrapper.find('form').trigger('submit.prevent');
        await flushPromises();

        expect(MemberService.createMember).toHaveBeenCalledTimes(1);
        
        // Assert it sends null for empty strings
        const calledPayload = MemberService.createMember.mock.calls[0][0];
        expect(calledPayload.first_name).toBe('John');
        expect(calledPayload.last_name).toBe('Doe');
        expect(calledPayload.middle_name).toBe(null);
        expect(calledPayload.gender).toBe('male');
        expect(calledPayload.marital_status).toBe('single'); // updated from single
        expect(calledPayload.employment_status).toBe('employed');

        expect(mockRouter.push).toHaveBeenCalledWith('/members');
    });

    it('handles API errors and shows alert', async () => {
        MemberService.createMember.mockRejectedValueOnce(new Error('API Error'));

        const wrapper = mount(MemberCreate, {
            global: { stubs: { RouterLink } }
        });

        await wrapper.find('form').trigger('submit.prevent');
        await flushPromises();

        expect(MemberService.createMember).toHaveBeenCalledTimes(1);
        expect(mockRouter.push).not.toHaveBeenCalled();
        expect(global.alert).toHaveBeenCalledWith('Failed to save member. Please check the inputs.');
    });
});
