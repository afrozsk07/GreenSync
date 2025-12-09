import { createServerSupabaseClient } from './supabase/server'
import { Profile } from '@/types/database'

export async function getCurrentUser() {
  const supabase = await createServerSupabaseClient()
  const { data: { user }, error } = await supabase.auth.getUser()
  
  if (error || !user) {
    return null
  }
  
  return user
}

export async function getUserProfile(): Promise<Profile | null> {
  const supabase = await createServerSupabaseClient()
  const user = await getCurrentUser()
  
  if (!user) {
    return null
  }
  
  const { data, error } = await supabase
    .from('profiles')
    .select('*')
    .eq('id', user.id)
    .single()
  
  if (error || !data) {
    return null
  }
  
  return data as Profile
}

export async function isAdmin(): Promise<boolean> {
  const profile = await getUserProfile()
  return profile?.role === 'admin'
}

export async function requireAuth() {
  const user = await getCurrentUser()
  if (!user) {
    throw new Error('Unauthorized')
  }
  return user
}

export async function requireAdmin() {
  const profile = await getUserProfile()
  if (!profile || profile.role !== 'admin') {
    throw new Error('Forbidden: Admin access required')
  }
  return profile
}
