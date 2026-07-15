import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import MembersList from '@/modules/members/views/MembersList.vue';
import MemberService from '@/modules/members/services/MemberService';

// Mock vue-router components
const RouterLink = {
  template: '<a><slot></slot></a>'
};

// Mock MemberService
vi.mock('@/modules/members/services/MemberService', () => ({
    default: {
        getMembers: vi.fn()
    }
}));

describe('MembersList.vue', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('shows loading state initially', () => {
        MemberService.getMembers.mockImplementation(() => new Promise(() => {})); // pending promise
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });
        expect(wrapper.text()).toContain('Loading members...');
    });

    it('renders members data', async () => {
        const mockMembers = [
            { id: 1, full_name: 'John Doe', gender: 'male', dob: '1990-01-01', phone: '0711111111', employment_status: 'employed', position: 'Member' },
            { id: 2, full_name: 'Jane Smith', gender: 'female', dob: '1995-05-05', phone: '0722222222', employment_status: 'student', position: 'Choir' }
        ];
        MemberService.getMembers.mockResolvedValue(mockMembers);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();

        expect(wrapper.text()).not.toContain('Loading members...');
        expect(wrapper.text()).toContain('John Doe');
        expect(wrapper.text()).toContain('Jane Smith');
        expect(wrapper.text()).toContain('Employed');
        expect(wrapper.text()).toContain('Student');
    });

    it('filters members by search query', async () => {
        const mockMembers = [
            { id: 1, full_name: 'John Doe' },
            { id: 2, full_name: 'Jane Smith' }
        ];
        MemberService.getMembers.mockResolvedValue(mockMembers);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();
        expect(wrapper.text()).toContain('John Doe');
        expect(wrapper.text()).toContain('Jane Smith');

        // Trigger search
        await wrapper.find('input[placeholder="Search members..."]').setValue('Jane');
        
        expect(wrapper.text()).not.toContain('John Doe');
        expect(wrapper.text()).toContain('Jane Smith');
    });

    it('filters members by status', async () => {
        const mockMembers = [
            { id: 1, full_name: 'John Doe', employment_status: 'employed' },
            { id: 2, full_name: 'Jane Smith', employment_status: 'student' }
        ];
        MemberService.getMembers.mockResolvedValue(mockMembers);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();

        // Trigger filter
        const select = wrapper.find('select');
        await select.setValue('student');
        
        expect(wrapper.text()).not.toContain('John Doe');
        expect(wrapper.text()).toContain('Jane Smith');
    });

    it('handles missing data gracefully', async () => {
        const mockMembers = [
            { id: 1, full_name: null, employment_status: 'unknown', dob: null, gender: null }
        ];
        MemberService.getMembers.mockResolvedValue(mockMembers);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();
        expect(wrapper.text()).toContain('??'); // missing name initials
        expect(wrapper.text()).toContain('Unknown'); // missing gender
        expect(wrapper.text()).toContain('Age unknown'); // missing dob
        expect(wrapper.text()).toContain('Unknown'); // unknown status formats to Unknown and uses badge-neutral
    });

    it('shows no members found message when empty', async () => {
        MemberService.getMembers.mockResolvedValue([]);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();
        expect(wrapper.text()).toContain('No members found.');
    });
});
