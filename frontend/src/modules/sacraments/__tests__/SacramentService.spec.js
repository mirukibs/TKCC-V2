import { describe, it, expect, vi, beforeEach } from 'vitest';
import SacramentService from '../services/SacramentService';
import api from '@/plugins/axios';

vi.mock('@/plugins/axios', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn(),
  }
}));

describe('SacramentService.js', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('fetches all sacraments', async () => {
    api.get.mockResolvedValue({ data: [{ id: 1 }] });
    const result = await SacramentService.getAll({ page: 1 });
    expect(api.get).toHaveBeenCalledWith('/sacraments', { params: { page: 1 } });
    expect(result).toEqual([{ id: 1 }]);
  });

  it('fetches a sacrament by id', async () => {
    api.get.mockResolvedValue({ data: { id: 1 } });
    const result = await SacramentService.getById(1);
    expect(api.get).toHaveBeenCalledWith('/sacraments/1');
    expect(result).toEqual({ id: 1 });
  });

  it('fetches a sacrament by member id', async () => {
    api.get.mockResolvedValue({ data: { id: 1 } });
    const result = await SacramentService.getByMemberId(1);
    expect(api.get).toHaveBeenCalledWith('/sacraments/member/1');
    expect(result).toEqual({ id: 1 });
  });

  it('creates a sacrament', async () => {
    api.post.mockResolvedValue({ data: { id: 1 } });
    const result = await SacramentService.create({ member_id: 1 });
    expect(api.post).toHaveBeenCalledWith('/sacraments', { member_id: 1 });
    expect(result).toEqual({ id: 1 });
  });

  it('updates a sacrament', async () => {
    api.put.mockResolvedValue({ data: { id: 1 } });
    const result = await SacramentService.update(1, { member_id: 1 });
    expect(api.put).toHaveBeenCalledWith('/sacraments/1', { member_id: 1 });
    expect(result).toEqual({ id: 1 });
  });

  it('deletes a sacrament', async () => {
    api.delete.mockResolvedValue({ data: null });
    const result = await SacramentService.delete(1);
    expect(api.delete).toHaveBeenCalledWith('/sacraments/1');
    expect(result).toBeNull();
  });
});
