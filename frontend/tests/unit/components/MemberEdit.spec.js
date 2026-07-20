import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import MemberEdit from '@/modules/members/views/MemberEdit.vue';
import MemberService from '@/modules/members/services/MemberService';
import HouseholdService from '@/modules/households/services/HouseholdService';

const RouterLink = { template: '<a><slot></slot></a>' };

const mockRouter = {
    push: vi.fn()
};

vi.mock('vue-router', () => ({
    useRouter: () => mockRouter,
    useRoute: () => ({ params: { id: '1' } })
}));

vi.mock('@/modules/members/services/MemberService', () => ({
    default: {
        getMember: vi.fn(),
        updateMember: vi.fn()
    }
}));

vi.mock('@/modules/households/services/HouseholdService', () => ({
    default: {
        getHouseholds: vi.fn()
    }
}));

describe('MemberEdit.vue', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        global.alert = vi.fn();
        
        HouseholdService.getHouseholds.mockResolvedValue({ data: [{ id: 1, name: 'Household 1', community_id: 10 }] });
        MemberService.getMember.mockResolvedValue({
            data: {
                id: 1,
                first_name: 'John',
                last_name: 'Doe',
                middle_name: 'Smith',
                dob: '1990-01-01',
                gender: 'male',
                marital_status: 'single',
                phone: '0712345678',
                position: 'Member',
                employment_status: 'employed',
                employment_notes: 'Software Engineer',
                household_id: 1
            }
        });
    });

    it('renders the form and populates data', async () => {
        const wrapper = mount(MemberEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();
        
        expect(wrapper.text()).toContain('Edit Member');
        const inputs = wrapper.findAll('input[type="text"]');
        expect(inputs[0].element.value).toBe('John'); // first_name
        expect(inputs[1].element.value).toBe('Doe'); // last_name
        
        const selects = wrapper.findAll('select');
        expect(selects[0].element.value).toBe('male'); // gender
    });

    it('submits updated payload and redirects on success', async () => {
        MemberService.updateMember.mockResolvedValueOnce({ data: { id: 1 } });

        const wrapper = mount(MemberEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        // Fill out form changes
        await wrapper.find('input[type="text"]').setValue('Johnny');

        await wrapper.find('form').trigger('submit.prevent');
        await flushPromises();

        expect(MemberService.updateMember).toHaveBeenCalledTimes(1);

        const calledPayload = MemberService.updateMember.mock.calls[0][1];
        expect(calledPayload.first_name).toBe('Johnny'); // changed
        expect(calledPayload.last_name).toBe('Doe'); // kept
        
        expect(mockRouter.push).toHaveBeenCalledWith('/members');
    });

    it('handles API errors on submit and shows alert', async () => {
        MemberService.updateMember.mockRejectedValueOnce(new Error('API Error'));

        const wrapper = mount(MemberEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        await wrapper.find('form').trigger('submit.prevent');
        await flushPromises();

        expect(MemberService.updateMember).toHaveBeenCalledTimes(1);
        expect(mockRouter.push).not.toHaveBeenCalled();
        expect(global.alert).toHaveBeenCalledWith('Failed to update member. Please check the inputs.');
    });

    it('handles missing data and sets defaults when fetching', async () => {
        MemberService.getMember.mockResolvedValue({
            data: { id: 1, first_name: 'Jane' } // other fields missing
        });

        const wrapper = mount(MemberEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        const inputs = wrapper.findAll('input[type="text"]');
        expect(inputs[0].element.value).toBe('Jane');
        expect(inputs[1].element.value).toBe(''); // last_name defaulted
    });

    it('handles error when loading data on mount', async () => {
        const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {});
        MemberService.getMember.mockRejectedValueOnce(new Error('Network error'));
        
        const wrapper = mount(MemberEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();
        
        expect(MemberService.getMember).toHaveBeenCalledTimes(1);
        expect(consoleSpy).toHaveBeenCalledWith('Failed to load data', expect.any(Error));
        consoleSpy.mockRestore();
    });

    it('handles household loading failure safely', async () => {
        HouseholdService.getHouseholds.mockRejectedValueOnce(new Error('No households'));
        
        const wrapper = mount(MemberEdit, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();
        
        expect(HouseholdService.getHouseholds).toHaveBeenCalledTimes(1);
        const householdSelect = wrapper.findAll('select')[3];
        expect(householdSelect.findAll('option').length).toBe(1); // Only the "No Household" default
    });
});
