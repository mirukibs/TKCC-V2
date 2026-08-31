import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import MemberDetail from '@/modules/members/views/MemberDetail.vue';
import MemberService from '@/modules/members/services/MemberService';

const RouterLink = { template: '<a><slot></slot></a>' };

vi.mock('vue-router', () => ({
    useRoute: () => ({
        params: { id: '1' }
    })
}));

vi.mock('@/modules/members/services/MemberService', () => ({
    default: {
        getMember: vi.fn()
    }
}));

describe('MemberDetail.vue', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('shows loading state initially', () => {
        MemberService.getMember.mockImplementation(() => new Promise(() => {}));
        const wrapper = mount(MemberDetail, {
            global: { stubs: { RouterLink } }
        });
        expect(wrapper.text()).toContain('Loading member profile...');
    });

    it('renders member details successfully', async () => {
        const mockMember = {
            id: 1,
            first_name: 'John',
            last_name: 'Doe',
            full_name: 'John Doe',
            gender: 'male',
            dob: '1990-01-01',
            phone: '0711111111',
            employment_status: 'employed',
            position: 'Member',
            employment_notes: 'Software Engineer',
            household: {
                id: 42,
                name: 'The Doe Family'
            },
            community: {
                id: 10,
                name: 'St. Peter Community'
            },
            zone: {
                id: 2,
                name: 'North Zone'
            },
            sacrament: {
                baptism_status: 'completed',
                baptism_date: '2000-01-01',
                baptism_place: 'St. John',
                confirmation_status: 'pending',
                marriage_status: 'none'
            }
        };
        MemberService.getMember.mockResolvedValueOnce({ data: mockMember });

        const wrapper = mount(MemberDetail, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();

        expect(MemberService.getMember).toHaveBeenCalledWith('1');
        
        expect(wrapper.text()).not.toContain('Loading member profile...');
        
        // Assert hero section
        expect(wrapper.text()).toContain('John Doe');
        expect(wrapper.text()).toContain('0711111111');
        expect(wrapper.text()).toContain('Employed');

        // Assert details section
        expect(wrapper.text()).toContain('John'); // first name
        expect(wrapper.text()).toContain('Doe'); // last name
        expect(wrapper.text()).toContain('1990-01-01');
        expect(wrapper.text()).toContain('male');
        expect(wrapper.text()).toContain('Software Engineer');
        
        // Assert aggregated details (Household, Community, Zone)
        expect(wrapper.text()).toContain('The Doe Family');
        expect(wrapper.text()).toContain('St. Peter Community');
        expect(wrapper.text()).toContain('North Zone');

        // Assert sacraments
        expect(wrapper.text()).toContain('Baptism');
        expect(wrapper.text()).toContain('completed');
        expect(wrapper.text()).toContain('2000-01-01 • St. John');
        expect(wrapper.text()).toContain('Confirmation');
        expect(wrapper.text()).toContain('pending');
        expect(wrapper.text()).toContain('Marriage');
        expect(wrapper.text()).toContain('none');
    });

    it('handles missing data gracefully', async () => {
        const mockMember = {
            id: 1,
            full_name: null,
            employment_status: null
        };
        MemberService.getMember.mockResolvedValueOnce({ data: mockMember });

        const wrapper = mount(MemberDetail, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();
        expect(wrapper.text()).toContain('??'); // missing initials
        expect(wrapper.text()).toContain('Unspecified'); // missing status
    });

    it('shows not found message when member is null or throws error', async () => {
        MemberService.getMember.mockRejectedValueOnce(new Error('Not found'));

        const wrapper = mount(MemberDetail, {
            global: { stubs: { RouterLink } }
        });

        await flushPromises();

        expect(wrapper.text()).toContain('Member not found.');
    });

    it('returns correct badge class for different employment statuses', async () => {
        // We can test this by mounting members with different statuses and checking classes
        const statuses = [
            { status: 'student', expectedClass: 'badge-primary' },
            { status: 'unemployed', expectedClass: 'badge-warning' },
            { status: 'unknown_status', expectedClass: 'badge-neutral' }
        ];

        for (const s of statuses) {
            MemberService.getMember.mockResolvedValueOnce({ 
                data: { id: 1, full_name: 'Test', employment_status: s.status } 
            });
            const wrapper = mount(MemberDetail, {
                global: { stubs: { RouterLink } }
            });
            await flushPromises();
            const badge = wrapper.find('.badge');
            expect(badge.classes()).toContain(s.expectedClass);
        }
    });
});
