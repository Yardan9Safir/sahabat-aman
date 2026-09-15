import './bootstrap';
import { initSidebar } from './sidebar';
import { initReplyForms } from './reply-form';

initSidebar({ root: '#appSidebar', collapsedKey: 'sa-sidebar-collapsed', navQuery: '#appSidebar nav a' });
initSidebar({ root: '#adminSidebar', collapsedKey: 'sa-admin-collapsed', navQuery: '#adminSidebar nav a' });
initReplyForms();