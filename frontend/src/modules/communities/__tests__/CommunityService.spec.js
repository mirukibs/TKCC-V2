import { describe, it, expect, vi } from 'vitest';
import CommunityService from '../services/CommunityService';
import axios from 'axios';

vi.mock('axios');

describe('CommunityService.js', () => {
  it('getAll calls GET /api/communities', async () => {
    axios.get.mockResolvedValue({ data: { data: [{ id: 1 }] } });
    const result = await CommunityService.getAll();
    expect(axios.get).toHaveBeenCalledWith('/api/communities');
    expect(result).toEqual([{ id: 1 }]);
  });

  it('getById calls GET /api/communities/:id', async () => {
    axios.get.mockResolvedValue({ data: { data: { id: 1 } } });
    const result = await CommunityService.getById(1);
    expect(axios.get).toHaveBeenCalledWith('/api/communities/1');
    expect(result).toEqual({ id: 1 });
  });

  it('create calls POST /api/communities', async () => {
    axios.post.mockResolvedValue({ data: { id: 1 } });
    const result = await CommunityService.create({ name: 'Test' });
    expect(axios.post).toHaveBeenCalledWith('/api/communities', { name: 'Test' });
    expect(result).toEqual({ id: 1 });
  });

  it('update calls PUT /api/communities/:id', async () => {
    axios.put.mockResolvedValue({ data: { id: 1 } });
    const result = await CommunityService.update(1, { name: 'Update' });
    expect(axios.put).toHaveBeenCalledWith('/api/communities/1', { name: 'Update' });
    expect(result).toEqual({ id: 1 });
  });

  it('delete calls DELETE /api/communities/:id', async () => {
    axios.delete.mockResolvedValue({});
    await CommunityService.delete(1);
    expect(axios.delete).toHaveBeenCalledWith('/api/communities/1');
  });
});
