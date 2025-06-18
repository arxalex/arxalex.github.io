import { Component } from '@angular/core';
import {FormsModule} from "@angular/forms";

@Component({
  selector: 'app-contact-form',
  imports: [FormsModule],
  templateUrl: './contact-form.html',
  styleUrl: './contact-form.scss'
})
export class ContactForm {
  contactData: ContactFormModel = {
    name: '',
    email: '',
    message: ''
  };

  onSubmit() {
    console.log('Form submitted:', this.contactData);
    this.contactData = { name: '', email: '', message: '' };
  }
}
