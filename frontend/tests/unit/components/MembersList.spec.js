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
        vi.useFakeTimers();
        const mockMembers = [
            { id: 1, full_name: 'John Doe' }
        ];
        MemberService.getMembers.mockResolvedValue(mockMembers);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();
        MemberService.getMembers.mockClear();

        // Trigger search
        await wrapper.find('input[placeholder="Search members..."]').setValue('Jane');
        vi.advanceTimersByTime(300);
        
        expect(MemberService.getMembers).toHaveBeenCalledWith({ search: 'Jane' });
        vi.useRealTimers();
    });

    it('filters members by status', async () => {
        vi.useFakeTimers();
        const mockMembers = [
            { id: 1, full_name: 'John Doe', employment_status: 'employed' }
        ];
        MemberService.getMembers.mockResolvedValue(mockMembers);
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();
        MemberService.getMembers.mockClear();

        // Trigger filter
        const select = wrapper.find('select');
        await select.setValue('student');
        vi.advanceTimersByTime(300);
        
        expect(MemberService.getMembers).toHaveBeenCalledWith({ status: 'student' });
        vi.useRealTimers();
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

    it('shows empty state when no members match search', async () => {
        MemberService.getMembers.mockResolvedValueOnce({ data: [] });
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        expect(wrapper.text()).toContain('No members found.');
    });

    it('handles api errors gracefully and logs to console', async () => {
        const consoleSpy = vi.spyOn(console, 'error').mockImplementation(() => {});
        MemberService.getMembers.mockRejectedValueOnce(new Error('Network error'));
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();

        expect(consoleSpy).toHaveBeenCalledWith('Failed to load members', expect.any(Error));
        consoleSpy.mockRestore();
    });

    it('returns correct badge class for different employment statuses', async () => {
        const mockMembers = [
            { id: 1, first_name: 'A', last_name: 'B', employment_status: 'student' },
            { id: 2, first_name: 'C', last_name: 'D', employment_status: 'unemployed' },
            { id: 3, first_name: 'E', last_name: 'F', employment_status: 'unknown' }
        ];
        MemberService.getMembers.mockResolvedValueOnce({ data: mockMembers });
        
        const wrapper = mount(MembersList, {
            global: { stubs: { RouterLink } }
        });
        
        await flushPromises();
        
        const badges = wrapper.findAll('.badge');
        expect(badges[0].classes()).toContain('badge-primary'); // student
        expect(badges[1].classes()).toContain('badge-warning'); // unemployed
        expect(badges[2].classes()).toContain('badge-neutral'); // unknown
    });
});
