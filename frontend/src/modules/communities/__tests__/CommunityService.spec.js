import { describe, it, expect, vi } from 'vitest';
import CommunityService from '../services/CommunityService';
import api from '@/plugins/axios';

vi.mock('@/plugins/axios');

describe('CommunityService.js', () => {
  it('getAll calls GET /communities', async () => {
    api.get.mockResolvedValue({ data: { data: [{ id: 1 }] } });
    const result = await CommunityService.getAll();
    expect(api.get).toHaveBeenCalledWith('/communities');
    expect(result).toEqual([{ id: 1 }]);
  });

  it('getById calls GET /communities/:id', async () => {
    api.get.mockResolvedValue({ data: { data: { id: 1 } } });
    const result = await CommunityService.getById(1);
    expect(api.get).toHaveBeenCalledWith('/communities/1');
    expect(result).toEqual({ id: 1 });
  });

  it('create calls POST /communities', async () => {
    api.post.mockResolvedValue({ data: { id: 1 } });
    const result = await CommunityService.create({ name: 'Test' });
    expect(api.post).toHaveBeenCalledWith('/communities', { name: 'Test' });
    expect(result).toEqual({ id: 1 });
  });

  it('update calls PUT /communities/:id', async () => {
    api.put.mockResolvedValue({ data: { id: 1 } });
    const result = await CommunityService.update(1, { name: 'Update' });
    expect(api.put).toHaveBeenCalledWith('/communities/1', { name: 'Update' });
    expect(result).toEqual({ id: 1 });
  });

  it('delete calls DELETE /communities/:id', async () => {
    api.delete.mockResolvedValue({});
    await CommunityService.delete(1);
    expect(api.delete).toHaveBeenCalledWith('/communities/1');
  });
});
